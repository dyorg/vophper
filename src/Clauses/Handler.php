<?php

class Vophper_Clauses_Handler
{
	private $condicao;

	private $stringCondicao;

	private $where;

	private $joins = array();

	private $groupBy;

	private $orderBy;

	private $limit;

	private function getLastPositionJoin()
	{
		if (empty($this->joins)) die ('Não existe nenhum join criado para setar um objeto de retorno');

		return count($this->joins) - 1;
	}

	private function joinAditionalHandler($sintaxAditional, $conditional)
	{
		$last = $this->getLastPositionJoin();
		$this->joins[$last]->addConditional($sintaxAditional . " " . $conditional);
	}

	private function JoinHandler($sintaxJoin, $tableRef, $conditional)
	{
		$this->joins[] = Vophper_Clauses_Join::Instance()->setSintaxJoin($sintaxJoin)->
		setTableReference($tableRef)->addConditional($conditional);
	}

	private function WhereHandler($stringFormated)
	{
		if (!isset($this->where)) $this->where = Vophper_Clauses_Where::Instance();

		$this->where->AddString($stringFormated);
	}

	public function getGroupBy()
	{
		return $this->groupBy;
	}

	public function setGroupBy($fields)
	{
		$this->groupBy = Vophper_Clauses_GroupBy::Instance()->AddFileds($fields);
	}

	public function getOrderBy()
	{
		return $this->orderBy;
	}

	public function setOrderBy($fields)
	{		
		$this->orderBy = Vophper_Clauses_OrderBy::Instance()->AddFileds($fields);
	}

	public function getLimit()
	{
		return $this->limit;
	}

	public function setLimit($rows, $start = '')
	{
		$this->limit = Vophper_Clauses_Limit::Instance()->setStart($start)->setRows($rows);
	}

	public function getJoins()
	{
		return $this->joins;
	}

	public function setInnerJoin($tableRef, $conditional)
	{
		$this->JoinHandler("INNER JOIN", $tableRef, $conditional);
	}

	public function setLeftJoin($tableRef, $conditional)
	{
		$this->JoinHandler("LEFT JOIN", $tableRef, $conditional);
	}

	public function setAndJoin($conditional)
	{
		$this->joinAditionalHandler(" AND", $conditional);
	}

	public function setOrJoin($conditional)
	{
		$this->joinAditionalHandler(" OR", $conditional);
	}

	public function setParamsJoin($params)
	{
		$last = $this->getLastPositionJoin();
		$this->joins[$last]->addParams($params);
	}

	public function getWhere()
	{
		return $this->where;
	}

	public function setWhere($string, $long = false)
	{
		if ($long === false){
			if (!isset($this->where))
				$this->WhereHandler(" WHERE $string");
			else 
				$this->WhereHandler(" AND $string");
		}
		else 
			 $this->WhereHandler(" $string");
	}

	public function setAndWhere($string)
	{
		$this->WhereHandler(" AND $string");
	}

	public function setOrWhere($string)
	{
		$this->WhereHandler(" OR $string");
	}

	public function setWhereParams($params)
	{
		try 
		{
			if (!isset($this->where)) throw new Exception('Must be usage function Where() before WhereParams()');
			
			$this->where->AddParams($params);
		
		} catch (Exception $e) 
		{
			 echo 'Exception caught: ',  $e->getMessage(), '<br/>';
		}
		
	}
}