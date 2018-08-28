<?php
	class sqldb {
		private $serverName = "RPT00024";
   		private $userName = "sa";
   		private $userPassword = "dsc2007!@";
		private $dbName = "eqcas";
	}
   
	public function connect() {
		$sql_Connect_str = "dblib:host=$this->serverName;dbname=$this->dbName";
		$dbConnection = new PDO($sql_Connect_str, $this->userName, $this->userPassword);
		$dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		return $dbConnection;
	}