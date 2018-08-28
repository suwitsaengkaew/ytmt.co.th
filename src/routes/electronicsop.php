<?php
header("Content-Type: text/html; charset=utf-8");

use \Psr\Http\Message\ServerRequestInterface as Request;

use \Psr\Http\Message\ResponseInterface as Response;


$app = new \Slim\App(['settings' => ['displayErrorDetails' => true]]);


//Get Section SOP Processes
$app->get('/api/process/{id}', function(Request $request, Response $response){
	
	$id = $request->getAttribute('id');
	
	$sql = "select processid, processname  from SOP_PROCESS where processid = $id";
	
	
	try {
		
		$db = new db();
		
		$db = $db->connect();
		
		
		$stmt = $db->query($sql);
		
		$processes = $stmt->fetchAll(PDO::FETCH_OBJ);
		
		$db = null;
		
		echo json_encode($processes);
		
		
	}
	catch (PDOException $e){
		
		echo '{"error": {"text": '.$e->getMessage().'}}';
		
	}
	
}
);


//Get Document Plant
$app->get('/api/documents/plant', function(Request $request, Response $response) {
	
	$sql = "SELECT DOC_Plant, count(*) AS CNT FROM DOCSYS group by DOC_Plant order by DOC_Plant";
	
	
	try {
		
		$db = new db();
		
		$db = $db->connect();
		
		
		$stmt = $db->query($sql);
		
		$plant = $stmt->fetchAll(PDO::FETCH_OBJ);
		
		$db = null;
		
		echo json_encode($plant);
		
	}
	
	catch (PDOException $e) {
		
		echo '{"error": {"text": '.$e->getMessage().'}}';
		
	}
	
}
);


//Get Document Section
$app->get('/api/documents/section', function(Request $request, Response $response) {
	
	$sql = "SELECT DOC_SEC, count(*) AS CNT FROM DOCSYS group by DOC_SEC order by DOC_SEC";
	
	
	try {
		
		$db = new db();
		
		$db = $db->connect();
		
		
		$stmt = $db->query($sql);
		
		$sec = $stmt->fetchAll(PDO::FETCH_OBJ);
		
		$db = null;
		
		echo json_encode($sec);
		
	}
	
	catch (PDOException $e) {
		
		echo '{"error": {"text": '.$e->getMessage().'}}';
		
	}
	
}
);


//Get Document Type
$app->get('/api/documents/type', function(Request $request, Response $response) {
	
	$sql = "SELECT DOC_TYPE, count(*) AS CNT FROM DOCSYS group by DOC_TYPE order by DOC_TYPE";
	
	
	try {
		
		$db = new db();
		
		$db = $db->connect();
		
		
		$stmt = $db->query($sql);
		
		$type = $stmt->fetchAll(PDO::FETCH_OBJ);
		
		$db = null;
		
		echo json_encode($type);
		
	}
	
	catch (PDOException $e) {
		
		echo '{"error": {"text": '.$e->getMessage().'}}';
		
	}
	
}
);


//Get Document Name
$app->get('/api/documents/{plant}/{section}/{type}', function(Request $request, Response $response){
	
	//$	processid = $request->getAttribute('processid');
	
	$plant = $request->getAttribute('plant');
	
	$section = $request->getAttribute('section');
	
	$type = $request->getAttribute('type');
	
	$sql = "SELECT DOC_Plant, DOC_SEC, DOC_TYPE, DOC_ID, DOC_NAME, DOC_URL 
            FROM QADB.DOCSYS
            WHERE DOC_Plant like '$plant'
            AND DOC_SEC like '$section'
            AND DOC_TYPE like '$type'";
	
	
	try {
		
		$db = new db();
		
		$db = $db->connect();
		
		
		$stmt = $db->query($sql);
		
		$document = $stmt->fetchAll(PDO::FETCH_OBJ);
		
		$db = null;
		
		echo json_encode($document);
		
		
	}
	catch (PDOException $e){
		
		echo '{"error": {"text": '.$e->getMessage().'}}';
		
	}
	
}
);


//Get SOP Document
$app->get('/api/sopdoc/{docid}', function(Request $request, Response $response){
	
	$docid = $request->getAttribute('docid');
	
	$sql = "SELECT SOP_DocId, SOP_Name, SOP_DocUrl FROM QADB.SOP_DOC where SOP_DocId = '$docid'";
	
	
	try {
		
		$db = new db();
		
		$db = $db->connect();
		
		
		$stmt = $db->query($sql);
		
		$sopdoc = $stmt->fetchAll(PDO::FETCH_OBJ);
		
		$db = null;
		
		echo json_encode($sopdoc);
		
		
	}
	catch (PDOException $e){
		
		echo '{"error": {"text": '.$e->getMessage().'}}';
		
	}
	
}
);


$app->get('/api/docuprint', function(Request $request, Response $response){
	
	//$	sql = "SELECT TOP 100 [docname] COLLATE Thai_CI_AS doc  FROM [eqcas].[dbo].[docureportview]";
	
	//$	sql = "SELECT CAST([docname] AS VARBINARY(MAX)) FROM [eqcas].[dbo].[cas_sdr_history]";
	
	$sql =  "SELECT name, description, sum(amount) as amount FROM [eqcas].[dbo].[docureportview] 
               WHERE trxdate >= '2017-01-01 00:00:00'
               and trxdate < '2017-02-01 00:00:00'
               group by name, description
               order by amount desc";
	
	try {
		
		$db = new sqldb();
		
		$db = $db->connect();
		
		
		$stmt = $db->query($sql);
		
		$docuprint = $stmt->fetchAll(PDO::FETCH_OBJ);
		
		$db = null;
		
		
		header("Content-Type: application/json;charset=utf-8");
		
		echo json_encode($docuprint, JSON_UNESCAPED_UNICODE);
		
		
	}
	catch (PDOException $e){
		
		echo '{"error": {"text": '.$e->getMessage().'}}';
		
	}
	
}
);


$app->get('/api/docuprint/{data}', function(Request $request, Response $response){
	
	$data = $request->getAttribute('data');
	
	//$	stdate = '2017-02-01 00:00:00';
	
	//$	eddate = '2017-03-01 00:00:00';
	
	$stdate = substr($data, 0, 10) .' 00:00:00';
	
	$eddate = substr($data, 10, 10) .' 00:00:00';
	
	$empid = substr($data, 20);
	
	
	$sql =  "SELECT trxdate, name, description, docname, pagecount, docdetails, amount FROM [eqcas].[dbo].[docureportview] 
                WHERE trxdate >= '$stdate'
                and trxdate < '$eddate'
                and name LIKE '$empid%'
                and name <> 'administrator'
                order by trxdate, amount desc";
	
	//e	cho $sql;
	
	try {
		
		$db = new sqldb();
		
		$db = $db->connect();
		
		
		$stmt = $db->query($sql);
		
		$docuprint = $stmt->fetchAll(PDO::FETCH_OBJ);
		
		$db = null;
		
		
		header("Content-Type: application/json;charset=utf-8");
		
		echo json_encode($docuprint, JSON_UNESCAPED_UNICODE);
		
		
	}
	catch (PDOException $e){
		
		echo '{"error": {"text": '.$e->getMessage().'}}';
		
	}
	
}
);

