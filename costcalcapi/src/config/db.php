<?php
class db {
	
	private $dbhost = '10.102.2.33';
	private $dbuser = 'QADB';
	private $dbpass = 'QADB';
	private $dbname = 'QADB';
	
	//Connect
	public function connect() {
		$mysql_connect_str = "mysql:host=$this->dbhost;dbname=$this->dbname;charset=utf8";
		$dbConnection = new PDO($mysql_connect_str, $this->dbuser, $this->dbpass);
		$dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		return $dbConnection;
	}
}

class acdb {
	
	private $dbhost = '10.102.2.33';
	private $dbuser = 'ACDB';
	private $dbpass = 'ACDB';
	private $dbname = 'ACDB';
	
	//Connect
	public function connect() {
		$mysql_connect_str = "mysql:host=$this->dbhost;dbname=$this->dbname;charset=utf8";
		$dbConnection = new PDO($mysql_connect_str, $this->dbuser, $this->dbpass);
		$dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		return $dbConnection;
	}
}

class sqldb {
	
	// 	private $serverName = "RPT00024";
	// 	private $userName = "sa";
	// 	private $userPassword = "dsc2007!@";
	// 	private $dbName = "eqcas";
	
	public function connect() {
		//$		conn = new PDO("sqlsrv:server=$this->serverName; Database = $this->dbName", $userName, $userPassword);
		//$		conn->setAttribute(PDO::SQLSRV_ATTR_ENCODING, PDO::SQLSRV_ENCODING_UTF8);
		// 		$sql_Connect_str = "sqlsrv:server=$this->serverName; Database = $this->dbName";
		// 		$dbConnection = new PDO($sql_Connect_str, $this->userName, $this->userPassword);
		//$		dbConnection->setAttribute(PDO::SQLSRV_ATTR_ENCODING, PDO::SQLSRV_ENCODING_UTF8);
		// 		$dbConnection->setAttribute(PDO::SQLSRV_ATTR_ENCODING, PDO::SQLSRV_ENCODING_UTF8);
		//r		eturn $dbConnection;
		
		$mssql = new PDO('dblib:host=RPT00024;dbname=eqcas', 'sa', 'dsc2007!@');
		//$		mssql->setAttribute(PDO::SQLSRV_ATTR_ENCODING, PDO::SQLSRV_ENCODING_UTF8);
		$mssql->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		return $mssql;
	}
}


class sqlserver2014 {
	public function connect() {
		$mssql = new PDO('dblib:host=RPTMSSQL2;dbname=ITDB', 'YTMT01', 'YTMT@01');
		$mssql->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		return $mssql;
	}
}
