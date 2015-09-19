<?php
require_once 'required/adodb5/adodb.inc.php';

class Vophper_Connection
{
	var $type;
	var $host;
	var $db;
	var $user;
	var $password;
	var $connection;

	function __construct()
	{
		#setar variaveis para conexão
		$this->MysqlDev();
		#definindo conexão
		 
		$this->connection = NewADOConnection($this->type);
		#servidor, usuario, senha e banco
		$this->connection->Connect($this->host,$this->user,$this->password,$this->db);
		#modo debug
		$this->connection->debug = false;
	}

	function MysqlDev()
	{
		$xml = Myapp_Config::xml_load('connection.xml');
		
		$this->type = $xml->mysql->server;
		$this->host = $xml->mysql->{APPLICATION_ENV}->host;
		$this->db = $xml->mysql->{APPLICATION_ENV}->database;
		$this->user = $xml->mysql->{APPLICATION_ENV}->user;
		$this->password = $xml->mysql->{APPLICATION_ENV}->password;
	}
}




