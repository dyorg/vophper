<?php

class Vophper_Clauses_Where
{
	private $strings;

	private $params = array();

	static public function Instance()
	{
		return new Vophper_Clauses_Where();
	}

	public function AddString($string)
	{
		$this->strings[] = $string;
		return true;
	}

	public function AddParams($params = array())
	{
		if (!is_array($params)) throw new Exception("The params parameter of the Where() must be an array");

		foreach ($params as $param)
		{
			$this->params[] = $param;
		}

		return true;
	}

	public function GetParams()
	{
		return $this->params;
	}

	public function __toString()
	{
		return implode('', $this->strings);
	}
}