<?php

abstract class Vophper_Mapper_Abstract 
{
	protected $_pk = array();
	
	public function __get($attribute)
	{				
		$method = 'get' . ucfirst($attribute);
			
		if(method_exists($this, $method))
		{
			return eval('return $this->' . $method . '();');
		}
		else
		{
			return null;
		}
	}
	
	public function _table()
	{
		return $this->_table;
	}

	public function _pk($key = null)
	{
		if (is_null($key))
			return $this->_pk;
		else
			return $this->_pk[$key];
	}
	
	public function _pkCount()
	{
		return count($this->_pk);
	}	
	
	public function __clone()
	{
		$objvars = get_object_vars($this);
		
		if ($objvars)
		{
			foreach ($objvars as $key => $objvar) 
			{			
				if (strstr($key, "relationship") !== false )
				{				
					eval ('$this->'.$key.' = clone $this->'.$key.';');	
				}
			}
		}
	}
}