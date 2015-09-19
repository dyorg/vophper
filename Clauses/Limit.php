<?php

class Vophper_Clauses_Limit
{		
	private $start = null;
	
	private $rows = 0;
	
	static public function Instance()
	{
		return new Vophper_Clauses_Limit();
	}
	
	public function setStart($number)
	{
		if (is_numeric($number)) $this->start = $number;	
		
		return $this;
	}
	/*
	public function getStart()
	{
		return $this->start;		
	}*/
		
	public function setRows($number)
	{
		if (is_numeric($number)) $this->rows = $number;	
		
		return $this;
	}
/*
	public function getRows()
	{
		return $this->rows;		
	}*/
	
	public function __toString()
	{
		if($this->start)
		{
			$output = " LIMIT {$this->start}, {$this->rows}";
		}
		else 
		{
			$output = " LIMIT {$this->rows}";
		}
		
		return $output;
	}
}