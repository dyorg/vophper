<?php

class Customer extends Vophper_Mapper_Abstract
{
	protected $_table = 'customer';

	protected $_pk = array('cus_id');

	private $cus_id;

	private $cus_name;

	private $cus_active;

	public function setCus_id($int = null)
	{
		$this->cus_id = $int;
		return true;
	}

	public function getCus_id()
	{
		return $this->cus_id;
	}

	public function setCus_name($string = null)
	{
		$this->cus_name = $string;
		return true;
	}

	public function getCus_name()
	{
		return $this->cus_name;
	}

	public function setCus_active($int = null)
	{
		$this->cus_active = $int;
		return true;
	}

	public function getCus_active()
	{
		return $this->cus_active;
	}
}

