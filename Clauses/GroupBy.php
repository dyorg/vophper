<?php

class Vophper_Clauses_GroupBy
{	
	private $fiels;
	
	static public function Instance()
	{
		return new Vophper_Clauses_GroupBy();
	}
	
	function AddFileds($string)
	{
		$this->fiels = $string;		
		
		return $this;
	}
	
	function __toString()
	{
		$string  = " GROUP BY " . $this->fiels;
		
		return $string;
	}
}