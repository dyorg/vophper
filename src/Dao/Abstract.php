<?php

abstract class Vophper_Dao_Abstract
{			
	protected $_objectMapped;
	
	public function __construct(Vophper_Mapper_Abstract $objectMapped = null)
	{
		$this->_objectMapped = $objectMapped;	
	}
	
	protected function __Delete(Vophper_Mapper_Abstract $objectMapped)
	{
		return Vophper_Manipulation_Instance::Delete($objectMapped);
	}
		
	public function Delete(Vophper_Mapper_Abstract $objectMapped)
	{
		return $this->__Delete($objectMapped)->Execute();
	}
		
	protected function __FetchAll()
	{
		return self::InstanceSelect();
	}		
	
	public function FetchAll()
	{
		return $this->__FetchAll()->Execute();
	}	
	
	protected function __FetchPairs($column1, $column2)
	{
		return self::InstanceSelect()->SelectPairs($column1, $column2);
	}		
	
	public function FetchPairs($column1, $column2)
	{
		return $this->__FetchPairs($column1, $column2)->Execute();
	}		
	
	protected function __Find($id)
	{ 					
		return self::InstanceSelect($id);
	}
	
	public function Find($id)
	{ 													
		return $this->__Find($id)->Execute();
	}		
	
	protected function __Save(Vophper_Mapper_Abstract $objectMapped)
	{
		return Vophper_Manipulation_Instance::Insert($objectMapped);
	}
	
	public function Save(Vophper_Mapper_Abstract $objectMapped)
	{
		return $this->__Save($objectMapped)->Execute();
	}
		
	protected function __Update(Vophper_Mapper_Abstract $objectMapped)
	{				
		return Vophper_Manipulation_Instance::Update($objectMapped);
	}
		
	public function Update(Vophper_Mapper_Abstract $objectMapped)
	{				
		return $this->__Update($objectMapped)->Execute();
	}
	
	private function InstanceSelect($id = null)
	{
		if (is_null($this->_objectMapped)) eval('$this->_objectMapped =  new '.str_replace("Dao", "", get_class($this)).';');
		
		$instance = Vophper_Manipulation_Instance::Select($this->_objectMapped);
		
		if (!is_null($id)) $instance->FindById($id);
		
		return $instance;
	}
}