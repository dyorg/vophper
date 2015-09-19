<?php

abstract class Vophper_Manipulation_Abstract extends Vophper_Manipulation_Clauses_Abstract
{
	protected $_connection;
	
	protected $_stmt;
	
	protected $_fields;
	
	protected $_query = null;
	
	protected $_params = array();
	
	protected $_objectMapped;
	
	protected $_abstractedFields;
	
	protected $_dateFields;
	
	protected $_ignoredFields = array();
	
	protected $_relationships;
	
	abstract protected function Prepare();	
	
	abstract protected function SetParams();
	
	abstract protected function Run();	
		
	
	public function __construct(Vophper_Mapper_Abstract $objectMapped)
	{					
		parent::__construct();
			
		$this->_objectMapped = clone $objectMapped;

		$this->initConnection();
	}	
	
	protected function Execute()
	{		
		$this->updateLocal();
				
		if (is_null($this->_query))
		{
			$this->Prepare();

			$this->SetParams();
		}  
		else 
		{
			$this->PrepareQuery();
		}
		
		return $this->Run();
	}
	
	private function initConnection()
	{
		$connection = new Vophper_Connection();
		
		$this->_connection = $connection->connection;
		
		$this->_connection->SetFetchMode(ADODB_FETCH_ASSOC);
	}
	
	protected function findDinamicObjects()
	{
		$vars = get_object_vars($this->_objectMapped);
		
		if ($vars)
		{
			foreach ($vars as $key => $var) 
			{			
				if (strstr($key, "relationship") !== false )
				{				
					$this->_relationships[] = $key;		
				}
			}
		}
	}
	
	public function IgnoreFields($string)
	{
		$this->_ignoredFields = explode(',', $string);
		return $this;
	}
	
	protected function AbstractFields()
	{		
		if (empty($this->_fields))
		{
			$methods = get_class_methods($this->_objectMapped);
			
			$ignoredFields = array_merge($this->_objectMapped->_pk(), $this->_ignoredFields);			
			
			foreach ($methods as $method)
			{						
				if (substr($method, 0, 3) == 'get')
				{
					$abstratedField = strtolower(str_replace("get", "", $method));	
					
					if (!in_array($abstratedField, $ignoredFields))
					{
						$this->_abstractedFields[] = $abstratedField;
					}
				}
			}			
		}
		else 
		{					
			$fields = str_replace(" ", "", $this->_fields);
			
			$this->_abstractedFields[] = explode(",", $fields);
		}
	}	
	
	public function Fields($fields)
	{
		$this->_fields = $this->_fields . $fields;
		return $this;
	}	
	
	public function Query($string)
	{
		$this->_query = $string;
		return $this;
	}
	
	protected function PrepareQuery()
	{		
		$this->_stmt = $this->_connection->Prepare($this->_query);
	}
	
	static protected function fieldToGetter($field)
	{		
		return 'get' . ucwords(strtolower($field)) . '()';	
	} 	
	
	public function AdodbHand()
	{
		return $this->_connection;
	}
}