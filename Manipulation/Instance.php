<?php

class Vophper_Manipulation_Instance
{
	static function Insert(Vophper_Mapper_Abstract $objectMapped)
	{
		return new Vophper_Manipulation_Insert($objectMapped);
	}

	static function Select(Vophper_Mapper_Abstract $objectMapped)
	{		
		return new Vophper_Manipulation_Select($objectMapped);
	}

	static function Update(Vophper_Mapper_Abstract $objectMapped)
	{
		return new Vophper_Manipulation_Update($objectMapped);
	}

	static function Delete(Vophper_Mapper_Abstract $objectMapped)
	{
		return new Vophper_Manipulation_Delete($objectMapped);
	}	
}