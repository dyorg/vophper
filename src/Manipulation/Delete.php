<?php

class Vophper_Manipulation_Delete extends Vophper_Manipulation_Abstract
{	
	public function Execute()
	{		
		return parent::Execute();
	}
		
	protected function Prepare()
	{
		$query  = " DELETE FROM " . $this->_objectMapped->_table() . " ";
				
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
	
	protected function SetParams()
	{
		if(isset($this->_where))
		{
			$this->_params = array_merge($this->_params, $this->_where->GetParams());
		}
		else 
		{
			eval('$this->_params[] = $this->_objectMapped->' . self::fieldToGetter($this->_objectMapped->_pk(0)).';');
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