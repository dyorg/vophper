<?php

class Vophper_Manipulation_Insert extends Vophper_Manipulation_Abstract
{			
	public function Execute()
	{
		$this->AbstractFields();
		
		return parent::Execute();
		/*
		$this->Prepare();
		
		$this->SetParams();
		
		return $this->Run();*/
	}
		
	protected function Prepare()
	{
		$query  = " INSERT INTO " . $this->_objectMapped->_table() . " (";
		
		$query .= implode(', ',$this->_abstractedFields);
		
		$query .= ") VALUES (";
		
		$params = array_pad(array(), count($this->_abstractedFields), '?');
		
		$query .= implode(', ',$params);
				
		$query .= ")";
		
		$this->_stmt = $this->_connection->Prepare($query);
	}
	
	protected function SetParams()
	{
		foreach ($this->_abstractedFields as $field) 
		{		
			eval('$this->_params[] = $this->_objectMapped->' . self::fieldToGetter($field) . ';');
		}
	}
	
	protected function Run()
	{
		$result = $this->_connection->Execute($this->_stmt, $this->_params); 
		
		if (!$result)
		{
			echo $this->_connection->ErrorMsg();	
			return false;
		}
	
		$id = $this->_connection->Insert_ID();
		
		return $id;
	}
}