<?php

class Vophper_Manipulation_Update extends Vophper_Manipulation_Abstract
{		
	public function Execute()
	{
		$this->AbstractFields();
		
		return parent::Execute();
	}
	
	protected function Prepare()
	{		
		$query = " UPDATE " . $this->_objectMapped->_table() . " SET ";
		
		$arr = $this->_abstractedFields;
		
		array_walk($arr, array($this, 'addCharacter'));
		
		$query .= implode(', ', $arr);
		
		if(isset($this->_where))
		{
			$query .= $this->_where;
		}		
		else
		{
			$query .= " WHERE " . $this->_objectMapped->_pk(0) . " = ? "; 
		}
		
		$this->_stmt = $this->_connection->Prepare($query);
	}
	
	private function addCharacter(&$item, $key)
	{
		$item = $item . ' = ? ';
	}	

	protected function SetParams()
	{
		/*if (!is_array($this->_dateFields)) {
			foreach ($this->_abstractedFields as $field) {
				//eval('$this->_params[] = $this->_objectMapped->' . self::fieldToGetter($field) . ';');
				$this->_params[] = $this->_objectMapped->$field;
				$slice = explode('/', $this->_objectMapped->$field); 
				//var_dump($day, $month, $year);
				
				var_dump($this->_connection->DBDate("$slice[2]-$slice[1]-$slice[0]"));
			}	
		} else {
			foreach ($this->_abstractedFields as $field) {				
				if (in_array($field, $this->_DateFields)){
					$this->_params[] = $this->_objectMapped->$field;
				}
				else {
					$this->_params[] = $this->_objectMapped->$field;
				}
				//eval('$this->_params[] = $this->_objectMapped->' . self::fieldToGetter($field) . ';');				
			}	
		}*/
		
		foreach ($this->_abstractedFields as $field) {
				//eval('$this->_params[] = $this->_objectMapped->' . self::fieldToGetter($field) . ';');
				$this->_params[] = $this->_objectMapped->$field;
			}	
			
		if(isset($this->_where))
		{
			$this->_params = array_merge($this->_params, $this->_where->GetParams());
		}
		else
		{
			//eval('$this->_params[] = $this->_objectMapped->' . self::fieldToGetter($this->_objectMapped->_pk(0)) . ';');
			$this->_params[] = $this->_objectMapped->{$this->_objectMapped->_pk(0)};
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
	
		return true;
	}
		
}