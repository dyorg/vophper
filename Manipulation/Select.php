<?php

class Vophper_Manipulation_Select extends Vophper_Manipulation_Abstract
{
	private $findById = false;

	private $unique = false;

	private $count = false;

	private $identifyByPrimaryKey = false;

	private $keyReturnArray = null;

	private $id = null;

	private $selectPairs = false;

	private $returnType;

	private $map_tables;

	private $cache;

	public function FindById($id)
	{
		$this->findById = true;
		$this->id = $id;
		return $this;
	}

	public function Count()
	{
		$this->count = true;
		return $this;
	}

	public function Unique()
	{
		parent::Limit(1);
		$this->unique = true;
		return $this;
	}

	public function IdentifyByPrimaryKey()
	{
		$this->identifyByPrimaryKey = true;
		return $this;
	}

	public function KeyReturnArray($field)
	{
		$this->keyReturnArray = $field;
		return $this;
	}

	public function SelectPairs($column1, $column2)
	{
		$this->_connection->SetFetchMode(ADODB_FETCH_NUM);

		$this->Fields("$column1, $column2");

		$this->selectPairs = true;

		return $this;
	}

	public function Execute($RETURNTYPE = 0)
	{
		/**
		 * $RETURNTYPE = 0 RETURN IN OBJECTS
		 * $RETURNTYPE = 1 RETURN IN ARRAY
		 * $RETURNTYPE = 2 RETURN THE RESULTSET
		 */

		isset($this->returnType) || $this->returnType = $RETURNTYPE;

		if ($RETURNTYPE == 0) $this->findDinamicObjects();

		return parent::Execute();
	}

	public function CacheExecute($SECONDS = 15, $RETURNTYPE = 0)
	{
		$this->_connection->cacheSecs = $SECONDS;

		$this->cache = true;

		return $this->Execute($RETURNTYPE);
	}

	protected function Prepare()
	{
		$query = " SELECT ";

		$query .= !isset($this->_fields) ? " * " : $this->_fields;

		$query .= " FROM " . $this->_objectMapped->_table();

		if ($this->_joins) $query .= implode('', $this->_joins);

		if ( $this->findById == true)
		{
			if ($this->_objectMapped->_pkCount() == 1) $query .= " WHERE " . $this->_objectMapped->_pk(0) . " = ? ";
		}
		elseif(isset($this->_where))
		{
			$query .= $this->_where;
		}

		if ($this->_groupby) $query .= $this->_groupby;

		if ($this->_orderby) $query .= $this->_orderby;

		if ($this->count)
		{
			$query = "SELECT count(*) FROM ($query) AS COUNT";
		}
		else
		{
			if ($this->_limit) $query .= $this->_limit;
		}

		$this->_stmt = $this->_connection->Prepare($query);
	}

	protected function SetParams()
	{
		if (!empty($this->_joins))
		{
			foreach ($this->_joins as $join)
			{
				$this->_params = array_merge($this->_params, $join->GetParams());
			}
		}

		if ( $this->findById == true)
		{
			$this->_params[] = $this->id;
		}
		elseif(isset($this->_where))
		{
			$this->_params = array_merge($this->_params, $this->_where->GetParams());
		}
	}

	protected function Run()
	{		
		if ($this->cache)
		{
			$result = $this->_connection->CacheExecute($this->_stmt, $this->_params);
		}
		else
		{
			$result = $this->_connection->Execute($this->_stmt, $this->_params);
		}

		if (!$result)
		{
			throw new Exception($this->_connection->ErrorMsg(),0);

			$this->_objectMapped = false;

			return false;
		}
		else
		{
			if ($result -> EOF)	{ $this->_objectMapped = false; return false; }

			if ($this->selectPairs)
			{
				while (!$result -> EOF)
				{
					$objects[$result -> fields[0]] = $result -> fields[1];

					$result->MoveNext();
				}

				return $objects;

			}
			elseif ($this->count)
			{
				return $result -> fields['count(*)'];
			}
			else
			{
				if ($this->returnType == 2) return $result;

				if ($this->returnType == 1) return $result->getRows();

				$rows = $result->getRows();

				$this->MapObjects($result);

				$mt = microtime(true);

				if (($result -> RecordCount() == 1 && $this->findById == true) || $this->unique == true)
				{
					$this->populateObjectMapped($this->_objectMapped, $rows[0]);

					return $this->_objectMapped;
				}
				else
				{
					if (!is_null($this->keyReturnArray))
					{
						$returnIndex = '$new->{$this->keyReturnArray}';
					}
					elseif ($this->identifyByPrimaryKey)
					{
						$returnIndex = '$new->{$this->_objectMapped->_pk(0)}';
					}
					else
					{
						$returnIndex = '';
					}

					$objects = null;
						
					for ($i = 0; $i < count($rows); $i++)
					{

						$new = clone $this->_objectMapped;

						$this->populateObjectMapped($new, $rows[$i]);
							
						eval('$objects['.$returnIndex.'] = $new;');
					}
						
					return $objects;
				}

				echo 'MICROTIME: '. (microtime(true) - $mt);
			}
		}
	}

	private function populateObjectMapped($objectMapped, $rows)
	{
		foreach ($rows as $row => $value)
		{
			$table = isset($this->map_tables[$row]) ? $this->map_tables[$row]: null;

			if (!is_null($table))
			{
				$field = ucwords(strtolower(str_replace('-','_',$row)));

				if ($table == 'base')
				{
					eval('$objectMapped->set'.$field.'($value);');
				}
				else
				{
					eval('$objectMapped->'.$table.'->set'.$field.'($value);');
				}
			}
		}

		return true;
	}

	private function MapObjects($result)
	{
		for ($i = 0; $i < $result->FieldCount(); $i++) {

			$column_name = ucwords(strtolower(str_replace('-','_',$result->FetchField($i)->name)));

			if (method_exists($this->_objectMapped, "set$column_name"))
			{
				$this->map_tables[$result->FetchField($i)->name] = 'base';
			}
			else
			{
				if (!empty($this->_relationships))
				{
					foreach ($this->_relationships as $key => $relationship)
					{
						if (method_exists($this->_objectMapped->$relationship, "set$column_name"))
						{
							$this->map_tables[$result->FetchField($i)->name] = $relationship;

							break 1;
						}
					}
				}
			}
		}

		return true;
	}
}