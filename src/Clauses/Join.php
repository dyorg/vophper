<?php

class Vophper_Clauses_Join
{	
	private $conditionals = array();
	
	private $params = array();
	
	private $objectReturn;
	
	private $tableReference;
	
	private $sintax;
	
	static public function Instance()
	{
		return new Vophper_Clauses_Join();
	} 
	
	public function setSintaxJoin($sintax)
	{
		$this->sintax = $sintax;
		return $this;
	}
	
	public function setTableReference($tableRef)
	{
		$this->tableReference = $tableRef;
		return $this;
	}
	
	public function addParams($params)
	{
		foreach ($params as $param) 
		{
			$this->params[] = $param;
		}
		return $this;
	}
	
	public function addConditional($conditional)
	{
		$this->conditionals[] = $conditional;
		return $this;
	}	
	
	function __toString()
	{
		$string  = !isset($this->sintax) ? " JOIN" : " " . $this->sintax;	
		
		$string .= " " . $this->tableReference;
		
		if (!empty($this->conditionals))
		{
			$string .= " ON"; 

			foreach ($this->conditionals as $conditional) 
			{
				$string .= " " . $conditional;
			}	
		}
		
		return $string;
	}
	
	function GetString()
	{
		$string  = !isset($this->sintax) ? " JOIN" : " " . $this->sintax;	
		
		$string .= " " . $this->tableReference;
		
		if (!empty($this->conditionals))
		{
			$string .= " ON"; 

			foreach ($this->conditionals as $conditional) 
			{
				$string .= " " . $conditional;
			}	
		}
		
		return $string;
	}
	
	function GetParams()
	{
		return $this->params;
	}
}