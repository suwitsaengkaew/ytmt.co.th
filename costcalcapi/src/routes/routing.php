<?php
header("Content-Type: text/html; charset=utf-8");
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app = new \Slim\App(['settings' => ['displayErrorDetails' => true]]);

// **********************************************************
// * MySQL DataBase	
// **********************************************************
// *** CostCalc *** //

$app->get('/getusertable', function(Request $request, Response $response){
	
	$sql = "SELECT User, Password, Officer FROM userTABLE";
	//$sql = "SELECT Food, Price FROM foodTABLE";

	try {
		$db = new acdb();
		$db = $db->connect();
		$stmt = $db->query($sql);
		$result = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo json_encode($result);
	}
	catch (PDOException $e){
		echo '{"error": {"text": '.$e->getMessage().'}}';
	}
});

$app->get('/getfoodtable', function(Request $request, Response $response){
	
	$sql = "SELECT Food, Price FROM foodTABLE";
	
	try {
		$db = new acdb();
		$db = $db->connect();
		$stmt = $db->query($sql);
		$result = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo json_encode($result);
	}
	catch (PDOException $e){
		echo '{"error": {"text": '.$e->getMessage().'}}';
	}
});

$app->get('/acdb/getexistingarticlespec', function(Request $request, Response $response){
	
	$sql = "SELECT article, count(*) as cnt FROM ACDB.article_spec group by article;";
	
	try {
		$db = new acdb();
		$db = $db->connect();
		$stmt = $db->query($sql);
		$result = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo json_encode($result);
	}
	catch (PDOException $e){
		echo '{"error": {"text": '.$e->getMessage().'}}';
	}
});

$app->get('/acdb/rmmaster', function(Request $request, Response $response){
	
	$conn = oci_connect('TSRB', 'TSRB', '10.102.1.22/FX01');
	$statement = "SELECT T1.KUBUN_NAME AS MATERIAL_KIND, T2.GENRYO_NO AS MATERIAL_NAME FROM TSRBC1000T T1, TSRBC1001T T2 ".
				"WHERE T1.GENRYO_KUBUN = T2.GENRYO_KUBUN ".
				"ORDER BY T1.KUBUN_NAME";
	
	$jsonData = array();	
    
	if (!$conn) {
        $e = oci_error();
        trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
    }
    else {
        $stid = oci_parse($conn, $statement);
        oci_execute($stid);

		$rows = array();
		while($row = oci_fetch_assoc($stid)) {
			$rows[] = $row;
		}
		echo json_encode($rows);
    }
	oci_close($conn);
});

$app->get('/acdb/rmkind', function(Request $request, Response $response){ //Oracle
	
	$conn = oci_connect('TSRB', 'TSRB', '10.102.1.22/FX01');
	$statement = "SELECT KUBUN_NAME AS MATERIAL_KIND FROM TSRBC1000T ".
				 "ORDER BY KUBUN_NAME";
	
	$jsonData = array();	
    
	if (!$conn) {
        $e = oci_error();
        trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
    }
    else {
        $stid = oci_parse($conn, $statement);
        oci_execute($stid);

		$rows = array();
		while($row = oci_fetch_assoc($stid)) {
			$rows[] = $row;
		}
		echo json_encode($rows);
    }
	oci_close($conn);
});

$app->post('/acdb/rmreceive', function(Request $request, Response $response){

	$sqlmax = "select MAX(uploadtime) + 1 AS max from RAW_PURCHASE";
	try {
		$db = new acdb();
		$db = $db->connect();
		$stmt = $db->query($sqlmax);
		$result = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		//print_r(array_values($result));	
		foreach ($result as $item) {
			$maxvalue = $item->max;
		}
		//echo $maxvalue;
	}
	catch (PDOException $e){
		echo '{"error": {"text": '.$e->getMessage().'}}';
	}

	$rmreceiveingdata = $request->getbody();

	$dataarray = json_decode($rmreceiveingdata, true);
	
	$arrlength = count($dataarray);
	//echo $arrlength;
	for ($x = 0; $x < $arrlength; $x++) {

		$item0 = $dataarray[$x]['item0'];
		$item1 = $dataarray[$x]['item1'];
		$item2 = $dataarray[$x]['item2'];
		$item3 = $dataarray[$x]['item3'];
		$item4 = $dataarray[$x]['item4'];
		$item5 = $dataarray[$x]['item5'];
		$item6 = $dataarray[$x]['item6'];
		$item7 = $dataarray[$x]['item7'];
		$item8 = $dataarray[$x]['item8'];
		$item9 = $dataarray[$x]['item9'];
		$item10 = $dataarray[$x]['item10'];
		$item11 = $dataarray[$x]['item11'];
		$item12 = $dataarray[$x]['item12'];
		$item13 = $maxvalue;


		$sql = "INSERT INTO ACDB.RAW_PURCHASE (DB_DATE, REC_DATE, MATERIAL_D, ACCOUNTING, VENDOR, MATERIAL_C, QTY, CURRENCY, PPV_DOC, PPV_LOC, AMNT_DOC, AMNT_LOC, AccCode, BusA, uploadtime) VALUES ".
			"(CURDATE(), :item0, :item1, :item2, :item3, :item4, :item5, :item6, :item7, :item8, :item9, :item10, :item11, :item12, :item13)";
			
		try {
			$db = new acdb();
			$db = $db->connect();
			$stmt = $db->prepare($sql);

			$stmt->bindParam(':item0', $item0);
			$stmt->bindParam(':item1', $item1);
			$stmt->bindParam(':item2', $item2);
			$stmt->bindParam(':item3', $item3);
			$stmt->bindParam(':item4', $item4);
			$stmt->bindParam(':item5', $item5);
			$stmt->bindParam(':item6', $item6);
			$stmt->bindParam(':item7', $item7);
			$stmt->bindParam(':item8', $item8);
			$stmt->bindParam(':item9', $item9);
			$stmt->bindParam(':item10', $item10);
			$stmt->bindParam(':item11', $item11);
			$stmt->bindParam(':item12', $item12);
			$stmt->bindParam(':item13', $item13);
			$stmt->execute();
			//echo '{"notice": {"text": "Data Added"}}';
		}
		catch (PDOException $e){
			echo '{"error": {"text": '.$e->getMessage().'}}';
		}
	}
});

//Insert Article Spec
$app->post('/acdb/insertartspec', function(Request $request, Response $response){
	
	$artspecdata = $request->getbody();
	$dataarray = json_decode($artspecdata, true);
	$arrlength = count($dataarray);
	//echo $arrlength;
	for ($x = 0; $x < $arrlength; $x++) {

		$article = $dataarray[$x]['article'];
		$part = $dataarray[$x]['part'];
		$part_name = $dataarray[$x]['part_name'];
		$material = $dataarray[$x]['material'];
		$weigh = floatval($dataarray[$x]['weigh']);
		$ver = $dataarray[$x]['ver'];

		$sql = "INSERT INTO ACDB.article_spec (article, part, part_name, material, weigh, ver) VALUES ".
			"(:article, :part, :part_name, :material, :weigh, :ver)";
		echo $sql;	
		try {
			$db = new acdb();
			$db = $db->connect();
			$stmt = $db->prepare($sql);

			$stmt->bindParam(':article', $article);
			$stmt->bindParam(':part', $part);
			$stmt->bindParam(':part_name', $part_name);
			$stmt->bindParam(':material', $material);
			$stmt->bindParam(':weigh', $weigh);
			$stmt->bindParam(':ver', $ver);
			$stmt->execute();
			//echo '{"notice": {"text": "Data Added"}}';
		}
		catch (PDOException $e){
			echo '{"error": {"text": '.$e->getMessage().'}}';
		}
	}
});

$app->post('/acdb/cloneartspec', function(Request $request, Response $response){
	
	$artspecdata = $request->getbody();
	$dataarray = json_decode($artspecdata, true);
	$arrlength = count($dataarray);
	//echo $arrlength;
	for ($x = 0; $x < $arrlength; $x++) {

		$article = $dataarray[$x]['article'];
		$part = $dataarray[$x]['part'];
		$part_name = $dataarray[$x]['part_name'];
		$material = $dataarray[$x]['material'];
		$weigh = floatval($dataarray[$x]['weigh']);
		$ver = $dataarray[$x]['ver'];

		$sql = "INSERT INTO ACDB.article_spec (article, part, part_name, material, weigh, ver) VALUES ".
			"(:article, :part, :part_name, :material, :weigh, :ver)";
		echo $sql;	
		try {
			$db = new acdb();
			$db = $db->connect();
			$stmt = $db->prepare($sql);

			$stmt->bindParam(':article', $article);
			$stmt->bindParam(':part', $part);
			$stmt->bindParam(':part_name', $part_name);
			$stmt->bindParam(':material', $material);
			$stmt->bindParam(':weigh', $weigh);
			$stmt->bindParam(':ver', $ver);
			$stmt->execute();
			//echo '{"notice": {"text": "Data Added"}}';
		}
		catch (PDOException $e){
			echo '{"error": {"text": '.$e->getMessage().'}}';
		}
	}
});

$app->put('/acdb/updateartspec', function(Request $request, Response $response){
	$rmreceiveingdata = $request->getbody();
	$dataarray = json_decode($rmreceiveingdata, true);
	$arrlength = count($dataarray);
	//echo $arrlength;
	//for ($x = 0; $x < $arrlength; $x++) {

	$article = $dataarray['article'];
	$part = $dataarray['part'];
	$part_name = $dataarray['part_name'];
	$material = $dataarray['material'];
	$weigh = floatval($dataarray['weigh']);

	//}	
	

	$sql = "UPDATE ACDB.article_spec ".
		  "SET weigh = $weigh ".
		  "WHERE article='$article' AND part='$part' AND part_name='$part_name' AND material='$material'";
			
	echo 'echo article -> '.$sql;
	try {
		$db = new acdb();
		$db = $db->connect();
		$stmt = $db->prepare($sql);
		$stmt->execute();
		echo '{"notice": {"text": "Data Added"}}';
	}
	catch (PDOException $e){
		echo '{"error": {"text": '.$e->getMessage().'}}';
	}
	
});

$app->get('/acdb/distinctpart', function(Request $request, Response $response){
	
	$sql = "SELECT distinct part, part_name FROM ACDB.article_spec"; //From article spec
	
	try {
		$db = new acdb();
		$db = $db->connect();
		$stmt = $db->query($sql);
		$result = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo json_encode($result);
	}
	catch (PDOException $e){
		echo '{"error": {"text": '.$e->getMessage().'}}';
	}
});

$app->get('/acdb/distinct_part', function(Request $request, Response $response){
	
	$sql = "SELECT distinct part FROM ACDB.article_spec"; //From article spec
	
	try {
		$db = new acdb();
		$db = $db->connect();
		$stmt = $db->query($sql);
		$result = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo json_encode($result);
	}
	catch (PDOException $e){
		echo '{"error": {"text": '.$e->getMessage().'}}';
	}
});

$app->get('/acdb/distinct_partname', function(Request $request, Response $response){
	
	$sql = "SELECT distinct part_name FROM ACDB.article_spec"; //From article spec
	
	try {
		$db = new acdb();
		$db = $db->connect();
		$stmt = $db->query($sql);
		$result = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo json_encode($result);
	}
	catch (PDOException $e){
		echo '{"error": {"text": '.$e->getMessage().'}}';
	}
});

$app->get('/acdb/distinct_material/{param}', function(Request $request, Response $response){

	$param = $request->getAttribute('param');
	$dataarray = explode(',',$param);
	$part = $dataarray[0];
	$part_name = $dataarray[1];

	$sql = "SELECT  distinct material FROM ACDB.article_spec where part = '$part' AND part_name = '$part_name'"; //From article spec
	
	try {
		$db = new acdb();
		$db = $db->connect();
		$stmt = $db->query($sql);
		$result = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo json_encode($result);
	}
	catch (PDOException $e){
		echo '{"error": {"text": '.$e->getMessage().'}}';
	}
});

$app->get('/acdb/distinctart', function(Request $request, Response $response){
	
	$sql = "select distinct(article) as art from ACDB.article_spec order by art"; //From article spec
	
	try {
		$db = new acdb();
		$db = $db->connect();
		$stmt = $db->query($sql);
		$result = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo json_encode($result);
	}
	catch (PDOException $e){
		echo '{"error": {"text": '.$e->getMessage().'}}';
	}
});


$app->get('/acdb/artspec/{art}', function(Request $request, Response $response){
	
	$art = $request->getAttribute('art');

	$sql = "select max(ver) as ver from ACDB.article_spec ".
		   "where article = '$art'";
	//echo $sql;
	try {
		$db = new acdb();
		$db = $db->connect();
		$stmt = $db->query($sql);
		$result = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;

		foreach ($result as $item) {
			$vermax = $item->ver;
		}
		//echo $vermax;
	}
	catch (PDOException $e){
		echo '{"error": {"text": '.$e->getMessage().'}}';
	}
	if ($vermax != null) {

		$sql = "select article, part, part_name, material, weigh, ver from ACDB.article_spec ".
		"where article = '$art' ".
		"and ver = $vermax"; //From article spec
		
		//echo $sql;
	
		try {
			$db = new acdb();
			$db = $db->connect();
			$stmt = $db->query($sql);
			$result = $stmt->fetchAll(PDO::FETCH_OBJ);
			$db = null;
			echo json_encode($result);
		}
		catch (PDOException $e){
			echo '{"error": {"text": '.$e->getMessage().'}}';
		}
	}
	else {
		$sql = "select article, part, part_name, material, weigh, ver from ACDB.article_spec ".
		"where article = '$art'";
		
		try {
			$db = new acdb();
			$db = $db->connect();
			$stmt = $db->query($sql);
			$result = $stmt->fetchAll(PDO::FETCH_OBJ);
			$db = null;
			echo json_encode($result);
		}
		catch (PDOException $e){
			echo '{"error": {"text": '.$e->getMessage().'}}';
		}
	}
});

// Get Compound Spec
$app->get('/acdb/compspec/{compno}', function(Request $request, Response $response){
	
	$compno = $request->getAttribute('compno');

	$sql = "select compound, RM as rm`, weigh, ver, user from ACDB.compound_spec where compound = '$compno'"; //From article spec
	
	try {
		$db = new acdb();
		$db = $db->connect();
		$stmt = $db->query($sql);
		$result = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo json_encode($result);
	}
	catch (PDOException $e){
		echo '{"error": {"text": '.$e->getMessage().'}}';
	}
});


// Get Compound Spec
$app->get('/acdb/distinctrmcompspec', function(Request $request, Response $response){
	
	$compno = $request->getAttribute('compno');

	$sql = "select distinct(RM) as rm from ACDB.compound_spec order by rm"; //From article spec
	
	try {
		$db = new acdb();
		$db = $db->connect();
		$stmt = $db->query($sql);
		$result = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo json_encode($result);
	}
	catch (PDOException $e){
		echo '{"error": {"text": '.$e->getMessage().'}}';
	}
});
// *** CostCalc *** //