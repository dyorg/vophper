<?php

class Vophper_Clauses_OrderBy
{	
	private $fiels;
	
	static public function Instance()
	{		
		return new Vophper_Clauses_OrderBy();
	}
	
	public function AddFileds($string)
	{
		$this->fiels = $string;		
		
		return $this;
	}
	
	public function __toString()
	{
		$string  = " ORDER BY " . $this->fiels;
		
		return $string;
	}
}