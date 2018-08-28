<?php

class sqldb {
	
	public function connect() {
		
		$mssql = new PDO('dblib:host=RPT00073;dbname=DBFlow7', 'sa', 'p@ssw0rd');
		//$		mssql->setAttribute(PDO::SQLSRV_ATTR_ENCODING, PDO::SQLSRV_ENCODING_UTF8);
		$mssql->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		return $mssql;
	}
}


