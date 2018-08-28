<?php
header("Content-Type: text/html; charset=utf-8");
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app = new \Slim\App(['settings' => ['displayErrorDetails' => true]]);

// **********************************************************
// * MySQL DataBase	
// **********************************************************
// **********************************
// Document Management System
// **********************************

$app->get('/documenttype', function(Request $request, Response $response){
	
	$sql = "SELECT type_code, type_name FROM [DBFlow7].[dbo].[document_type]";
	
	try {
		$db = new sqldb();
		$db = $db->connect();
		$stmt = $db->query($sql);
		$processes = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo json_encode($processes);
	}
	catch (PDOException $e){
		echo '{"error": {"text": '.$e->getMessage().'}}';
	}
});

//Get Document Section
$app->get('/documentsection', function(Request $request, Response $response) {
	$sql = "SELECT org_code, org_name FROM [DBFlow7].[dbo].[org_detail] ".
	       "WHERE org_code in ('1000587', '1000588', '1000589', '1000590', '1000591')";

	try {
		$db = new sqldb();
		$db = $db->connect();
		$stmt = $db->query($sql);
		$sec = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo json_encode($sec);
	}
	catch (PDOException $e) {
		echo '{"error": {"text": '.$e->getMessage().'}}';
	}
});

$app->get('/documentlist', function(Request $request, Response $response) {

	// $org_id = $request->getAttribute('org_id');
	$sql = "SELECT T1.org_code, T1.org_name, T2.doc_id, T2.doc_number, T2.doc_subject, T2.issue_status, T2.doc_type FROM [DBFlow7].[dbo].[org_detail] T1, [DBFlow7].[dbo].[document_detail] T2 ".
			// "WHERE T1.org_code = '$org_id' ".
			"WHERE T2.doc_type in ('SOP','WI') ".
			"AND T2.issue_status = 'ISSUE' ".
			"AND T1.org_code = T2.doc_org";

	try {
		$db = new sqldb();
		$db = $db->connect();
		$stmt = $db->query($sql);
		$sec = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo json_encode($sec);
	}
	catch (PDOException $e) {
		echo '{"error": {"text": '.$e->getMessage().'}}';
	}
});


$app->get('/documentfile', function(Request $request, Response $response) {
	
		// $doc_id = $request->getAttribute('doc_id');
		$sql = "SELECT doc_id ,file_name ,file_path ,file_url  FROM [DBFlow7].[dbo].[common_convert] WHERE file_convert_status = 'CONVERT TO PS COMPLETED'";
  				// "WHERE doc_id = '$doc_id'";
	
		try {
			$db = new sqldb();
			$db = $db->connect();
			$stmt = $db->query($sql);
			$sec = $stmt->fetchAll(PDO::FETCH_OBJ);
			$db = null;
			echo json_encode($sec);
		}
		catch (PDOException $e) {
			echo '{"error": {"text": '.$e->getMessage().'}}';
		}
	});


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
});

//Get All Sections
$app->get('/api/sections/{plant}', function(Request $request, Response $response){
	
	$plant = $request->getAttribute('plant');
	$sql = "select DOC_SEC, count(*) AS CNT  from DOCSYS where DOC_Plant = '$plant' group by DOC_SEC";
	
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
});

//Get All DocType
$app->get('/api/doctype/{plant}/{section}', function(Request $request, Response $response){
	
	$plant = $request->getAttribute('plant');
	$section = $request->getAttribute('section');
	$sql = "select DOC_TYPE, count(*) AS CNT  from DOCSYS where DOC_Plant = '$plant' and DOC_SEC = '$section' group by DOC_TYPE";
	
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
});

//Get All Document List
$app->get('/api/doclist/{plant}/{section}/{doctype}', function(Request $request, Response $response){
	
	$plant = $request->getAttribute('plant');
	$section = $request->getAttribute('section');
	$doctype = $request->getAttribute('doctype');
	$sql = "select DOC_ID, DOC_NAME, DOC_URL  from DOCSYS where DOC_Plant = '$plant' and DOC_SEC = '$section' and DOC_TYPE='$doctype'";
	
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
});


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
});



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
});

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
});



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
});
// **********************************
// Document Management System
// **********************************
