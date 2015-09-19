<?php

/**
 * Searching customers
 * return a customer list
 */
$customerDao = new CustomerDao();
$customerList = $dao->FetchSearch();


/**
 * Creating new customer
 */
$customerVo = new CustomerVO();
$customerVo->name = 'John';

$customerDao = new CustomerDao();
$customerDao->save($customerVo); // saved