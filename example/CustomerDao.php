<?php

class CustomerDao extends Vophper_Dao_Abstract
{
	public function FetchSearch()
	{
		$i = parent::__FetchAll();
		$i->Limit(10);
		return $i->Execute();
	}

	public function FetchAllPairs()
	{
        $i = parent::__FetchPairs('cus_id', 'cus_name')
		->OrderBy('cus_name')
		->Execute();
		return $i;
	}

	public function Delete(Customer $vo)
	{
		$vo->setCus_active(0);
		return parent::Update($vo);
	}

	public function Save(Customer $vo)
	{
		return parent::Save($vo);
	}

	public function Update(Customer $vo)
	{
		return parent::Update($vo);
	}
}

