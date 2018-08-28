<?php
header("Content-Type: text/html; charset=utf-8");
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app = new \Slim\App(['settings' => ['displayErrorDetails' => true]]);

// **********************************
// Document Management System
// **********************************

//Get Section SOP Processes
$app->get('/api/ora', function(Request $request, Response $response){
	
	$conn = oci_connect('TSRB', 'TSRB', '10.102.1.22/FX01');
	
    if (!$conn) {
        $e = oci_error();
        trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
    }
    else {
        $stid = oci_parse($conn, 'SELECT * FROM TSPEXR001T');
        oci_execute($stid);
        $row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS);
        echo json_encode($row);
    }
	// try {
	// 	$db = new db();
	// 	$db = $db->connect();
	// 	$stmt = $db->query($sql);
	// 	$processes = $stmt->fetchAll(PDO::FETCH_OBJ);
	// 	$db = null;
	// 	echo json_encode($processes);
	// }
	// catch (PDOException $e){
	// 	echo '{"error": {"text": '.$e->getMessage().'}}';
	// }
});
