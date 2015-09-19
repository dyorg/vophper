<?php

abstract class Vophper_Manipulation_Clauses_Abstract
{
	protected $_clauses;

	protected $_joins;

	protected $_where;

	protected $_limit;

	protected $_groupby;

	protected $_orderby;

	static private $param_replace;

	static private $param_search = array();

	public function __construct()
	{
		$this->_clauses = new Vophper_Clauses_Handler();
	}

	protected function updateLocal()
	{
		$this->_joins = $this->_clauses->getJoins();

		$this->_where = $this->_clauses->getWhere();

		$this->_limit = $this->_clauses->getLimit();

		$this->_groupby = $this->_clauses->getGroupBy();

		$this->_orderby = $this->_clauses->getOrderBy();
	}

	public function GroupBy($fields)
	{
		$this->_clauses->setGroupBy($fields);
		return $this;
	}

	public function OrderBy($fields)
	{
		$this->_clauses->setOrderBy($fields);
		return $this;
	}

	public function Limit($rows, $start = '')
	{
		$this->_clauses->setLimit($rows, $start);
		return $this;
	}

	public function InnerJoin($tableRef, $conditional)
	{
		$this->_clauses->setInnerJoin($tableRef, $conditional);
		
		if (func_num_args() > 2)
		{
			$parameters = array_slice(func_get_args(), 2);	
			$this->JoinParams($parameters);
		}
				
		return $this;
	}

	public function LeftJoin($tableRef, $conditional)
	{
		$this->_clauses->setLeftJoin($tableRef, $conditional);

		if (func_num_args() > 2)
		{
			$parameters = array_slice(func_get_args(), 2);	
			$this->JoinParams($parameters);
		}
				
		return $this;
	}

	public function AndJoin($conditional)
	{
		$this->_clauses->setAndJoin($conditional);
		
		if (func_num_args() > 2)
		{
			$parameters = array_slice(func_get_args(), 2);	
			$this->JoinParams($parameters);
		}
				
		return $this;
	}

	public function OrJoin($conditional)
	{
		$this->_clauses->setOrJoin($conditional);

		if (func_num_args() > 2)
		{
			$parameters = array_slice(func_get_args(), 2);	
			$this->JoinParams($parameters);
		}
		
		return $this;
	}

	public function JoinParams($params)
	{
		$this->_clauses->setParamsJoin($params);
		return $this;
	}

	public function Where($string)
	{
		$this->_clauses->setWhere($string);

		if (func_num_args() > 1)
		{
			$parameters = array_slice(func_get_args(), 1);	
			$this->WhereParams($parameters);
		}

		return $this;
	}

	public function WhereLong($string)
	{
		$this->_clauses->setWhere($string, true);

		if (func_num_args() > 1)
		{
			$parameters = array_slice(func_get_args(), 1);	
			$this->WhereParams($parameters);
		}

		return $this;
	}

	public function AndWhere($string)
	{
		$this->_clauses->setAndWhere($string);
		
		if (func_num_args() > 1)
		{
			$parameters = array_slice(func_get_args(), 1);	
			$this->WhereParams($parameters);
		}
				
		return $this;
	}

	public function OrWhere($string)
	{
		$this->_clauses->setOrWhere($string);
		
		if (func_num_args() > 1)
		{
			$parameters = array_slice(func_get_args(), 1);	
			$this->WhereParams($parameters);
		}
				
		return $this;
	}

	public function WhereParams($params)
	{
		if (self::$param_replace)
		{
			foreach ($params as &$param) {
				if (in_array($param, self::$param_search)) $param = self::$param_replace;
			}
		}

		$this->_clauses->setWhereParams($params);
		return $this;
	}

	static public function ParamsReplace(array $search, $replace)
	{
		self::$param_search = $search;
		self::$param_replace = $replace;
	}

	static public function ParamsReplaceClean()
	{
		unset($this->param_search);
		unset($this->param_replace);
	}
}