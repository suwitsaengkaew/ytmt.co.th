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
// **********************************************************
// * MySQL DataBase	
// **********************************************************


// **********************************************************
// * MSSQL DataBase	
// **********************************************************

// **********************************
//DocuPrint Report
// **********************************

  
$app->get('/api/tempactual', function(Request $request, Response $response){
	//$sql =  "SELECT dept, UPPER(name) AS deptname, count(*) AS cnt FROM [eqcas].[dbo].[docu_userview] group by dept, name order by name";
	$sql = "SELECT ACTUAL, REC_DATE, LOCATION, PLANT FROM [ITDB].[dbo].[TEMPERATURE_MORNITOR] ".
  	"WHERE REC_DATE = (SELECT MAX(REC_DATE) AS MAXDATE FROM [ITDB].[dbo].[TEMPERATURE_MORNITOR])";
	try {
		$db = new sqlserver2014();
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
});


// Get docuPrinte data by employee
$app->get('/api/docuprint/employee/{data}', function(Request $request, Response $response){
	$data = $request->getAttribute('data');
	$copy = substr($data, 0, 1);
	$color = substr($data, 1, 1);
	$stdate = substr($data, 2, 10) .' 00:00:00';
	$eddate = substr($data, 12, 10) .' 00:00:00';
	$empid = substr($data, 22);

	switch ($copy) {
		case '0':
			 case '0':
			 $_copy = '%';
			break;
		case '1':
			$_copy = 'Copy';
			break;
		case '2':
			$_copy = 'Print';
			break;	
	}

	switch ($color) {
		case '0':
			$_color = '%';
			break;
		case '1':
			$_color = 'Color';
			break;
		case '2':
			$_color = 'B/W';
			break;	
	}
	$sql =  "SELECT trxdate, accountname, accountfullname, docname, pagecount, amount, pagesize, color FROM [eqcas].[dbo].[view_getreport] 
                WHERE trxdate >= '$stdate'
                and trxdate < '$eddate'
                and accountname LIKE '$empid%'
				and trxtype LIKE '$_copy%'
                and color LIKE '$_color%'
				and accountfullname <> 'administrator'
                order by trxdate";
	
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
});

// Get docuPrinte data by section
$app->get('/api/docuprint/dept/{data}', function(Request $request, Response $response){
	$data = $request->getAttribute('data');
	$copy = substr($data, 0, 1);
	$color = substr($data, 1, 1);
	$stdate = substr($data, 2, 10) .' 00:00:00';
	$eddate = substr($data, 12, 10) .' 00:00:00';
	$section = substr($data, 22);

	switch ($copy) {
		case '0':
			 $_copy = '%';
			break;
		case '1':
			$_copy = 'Copy';
			break;
		case '2':
			$_copy = 'Print';
			break;	
	}

	switch ($color) {
		case '0':
			$_color = '%';
			break;
		case '1':
			$_color = 'Color';
			break;
		case '2':
			$_color = 'B/W';
			break;	
	}

	$sql =  "SELECT trxdate, departmentname, accountname, accountfullname, docname, pagecount, amount, pagesize, color FROM [eqcas].[dbo].[view_getreport] 
                WHERE trxdate >= '$stdate'
                and trxdate < '$eddate'
                and departmentid LIKE '%$section%'
				and trxtype LIKE '$_copy%'
                and color LIKE '$_color%'
                and accountfullname <> 'administrator'
                order by trxdate";
	
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
});


// Get docuPrint Department Data
$app->get('/api/docuprints/getdept', function(Request $request, Response $response){
	$sql =  "SELECT dept, UPPER(name) AS deptname, count(*) AS cnt FROM [eqcas].[dbo].[docu_userview] group by dept, name order by name";
	
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
});

// **********************************
//DocuPrint Report
// **********************************
// **********************************************************
// * MSSQL DataBase	
// **********************************************************


// **********************************************************
// * Oracle DataBase	
// **********************************************************
$app->get('/api/ora', function(Request $request, Response $response){
	
	$conn = oci_connect('TSRB', 'TSRB', '10.102.1.22/FX01');
    if (!$conn) {
        $e = oci_error();
        trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
    }
    else {
        $stid = oci_parse($conn, 'SELECT * FROM TSPEXR001T');
        oci_execute($stid);
        //$row = oci_fetch_array($stid); //, OCI_ASSOC+OCI_RETURN_NULLS);
        //echo json_encode($row);

		$rows = array();
		while($r = oci_fetch_assoc($stid)) {
			$rows[] = $r;
		}
		echo json_encode($rows);
		//$locations =(json_encode($rows))
    }
	oci_close($conn);
});

// Procution result AM
$app->get('/api/productionam', function(Request $request, Response $response){
	
	$conn = oci_connect('TSRB', 'TSRB', '10.102.1.22/FX01');
	$statement = "SELECT TO_CHAR(TRUNC(SYSDATE)-1,'YYYY-MM-DD')||' (N), '|| CURE.CNT ||'(PC_CURE),  '|| KURA.CNT || '(PC_WH), '|| KURAA.CNT || '(PC(RE)_WH), '|| " .
      "NG.CNT ||'(PC_NG),  '|| TBCURE.CNT || '(TB_CURE),  '|| TBKURA.CNT||'(TB_WH),  '|| TBNG.CNT||'(TB_NG)' AS RESULT " .
      "FROM " .
      "(SELECT COUNT(*) CNT FROM TSRBE0511T  WHERE CURE_END_TS >= TO_DATE(TO_CHAR(sysdate-1,'YYYY-MM-DD')||' 08:00:00','YYYY-MM-DD HH24:MI:SS') " .
      "AND CURE_END_TS < TO_DATE(TO_CHAR(sysdate,'YYYY-MM-DD')||' 08:00:00','YYYY-MM-DD HH24:MI:SS')) CURE, " .
      "(SELECT COUNT(*) CNT FROM TSRKA2002TT WHERE JSK_TS >= TO_DATE(TO_CHAR(sysdate-1,'YYYY-MM-DD')||' 08:00:00','YYYY-MM-DD HH24:MI:SS') " .
      "AND JSK_TS < TO_DATE(TO_CHAR(sysdate,'YYYY-MM-DD')||' 08:00:00','YYYY-MM-DD HH24:MI:SS') " .
      "AND SEIBAN IS NOT NULL " .
      "AND return_kubun is null) KURA, " .
      "(SELECT COUNT(*) CNT FROM TSRKA2002TT WHERE JSK_TS >= TO_DATE(TO_CHAR(sysdate-1,'YYYY-MM-DD')||' 08:00:00','YYYY-MM-DD HH24:MI:SS') " .
      "AND JSK_TS < TO_DATE(TO_CHAR(sysdate,'YYYY-MM-DD')||' 08:00:00','YYYY-MM-DD HH24:MI:SS') " .
      "AND SEIBAN IS NOT NULL " .
      "AND return_kubun is not null) KURAA, " .
      "(SELECT COUNT(*) CNT FROM TSRQA0006TT WHERE SYORI_TS >= TO_DATE(TO_CHAR(sysdate-1,'YYYY-MM-DD')||' 08:00:00','YYYY-MM-DD HH24:MI:SS') " .
      "AND SYORI_TS < TO_DATE(TO_CHAR(sysdate,'YYYY-MM-DD')||' 08:00:00','YYYY-MM-DD HH24:MI:SS')) NG, " .
      "(SELECT COUNT(*) CNT FROM TSRBE0402T WHERE CURE_END_TS >= TO_DATE(TO_CHAR(sysdate-1,'YYYY-MM-DD')||' 08:00:00','YYYY-MM-DD HH24:MI:SS') " .
      "AND CURE_END_TS < TO_DATE(TO_CHAR(sysdate,'YYYY-MM-DD')||' 08:00:00','YYYY-MM-DD HH24:MI:SS')) TBCURE, " .
      "(SELECT COUNT(*) CNT FROM TSRKA2002T WHERE JSK_TS >= TO_DATE(TO_CHAR(sysdate-1,'YYYY-MM-DD')||' 08:00:00','YYYY-MM-DD HH24:MI:SS') " .
      "AND JSK_TS < TO_DATE(TO_CHAR(sysdate,'YYYY-MM-DD')||' 08:00:00','YYYY-MM-DD HH24:MI:SS') " .
      "AND SEIBAN IS NOT NULL) TBKURA, " .
      "(SELECT COUNT(*) CNT FROM TSRQA0006T WHERE SYORI_TS >= TO_DATE(TO_CHAR(sysdate-1,'YYYY-MM-DD')||' 08:00:00','YYYY-MM-DD HH24:MI:SS') " .
      "AND SYORI_TS < TO_DATE(TO_CHAR(sysdate,'YYYY-MM-DD')||' 08:00:00','YYYY-MM-DD HH24:MI:SS')) TBNG";
	
	if (!$conn) {
        $e = oci_error();
        trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
    }
    else {
        $stid = oci_parse($conn, $statement);
        oci_execute($stid);

		$rows = array();
		while($r = oci_fetch_assoc($stid)) {
			$rows[] = $r;
		}
		echo json_encode($rows);
    }
	oci_close($conn);
});

// Procution result scrap PC/LT AM
$app->get('/api/scrappcam', function(Request $request, Response $response){
	
	$conn = oci_connect('TSRB', 'TSRB', '10.102.1.22/FX01');
	$statement = "SELECT  (REP.CNT ||'('|| ".
         "REP.ART ||'('|| ".
         "REP.UPP ||'))') AS ROW1 FROM ".
        "(SELECT ART, UPP, COUNT(*) CNT FROM ".
          "(SELECT SC.ART_NO ART, sc.bld_lvl_cd LBL,  CONCAT(NVL(qa.item_mei, sc.item_cd), ".
            "(SELECT ".
              "CASE ".
              "WHEN UF.STATIC_CLASS = 1 THEN 'S' ".
              "WHEN UF.COUPLE_CLASS = 1 THEN 'C' ".
              "WHEN UF.UPPER_CLASS = 1 THEN 'U' ".
              "WHEN UF.LOWER_CLASS = 1 THEN 'L' ".
              "END AS UPP ".
              "FROM UFSPC0052T UF ".
              "WHERE UF.LBL_BARCODE = sc.bld_lvl_cd ".
            "AND ROWNUM = 1)) UPP ".
          "FROM TSRQA0006TT SC, TSRQA0003T QA ".
          "WHERE sc.syori_ts >= TO_DATE(TO_CHAR(sysdate-1,'YYYY-MM-DD')||' 08:00:00','YYYY-MM-DD HH24:MI:SS') ".
          "AND sc.syori_ts < TO_DATE(TO_CHAR(sysdate,'YYYY-MM-DD')||' 08:00:00','YYYY-MM-DD HH24:MI:SS') ".
          "AND sc.item_cd = qa.item_cd(+) ".
          "ORDER BY SC.syori_ts) ".
        "GROUP BY  ART, UPP ".
        "ORDER BY CNT DESC) REP ".
        "WHERE ROWNUM < 6";
	
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


// Procution result scrap TBS AM
$app->get('/api/scraptbam', function(Request $request, Response $response){
	
	$conn = oci_connect('TSRB', 'TSRB', '10.102.1.22/FX01');
	$statement = "SELECT  (REP.CNT ||'('|| " .
         "REP.ART ||'('|| " .
         "REP.UPP ||'))') AS ROW1 FROM " .
        "(SELECT ART, UPP, COUNT(*) CNT FROM " .
          "(SELECT SC.ART_NO ART, sc.bld_lvl_cd LBL,  CONCAT(NVL(qa.item_mei, sc.item_cd), " .
            "(SELECT " .
              "CASE " .
              "WHEN UF.STATIC_CLASS = 1 THEN 'S' " .
              "WHEN UF.COUPLE_CLASS = 1 THEN 'C' " .
              "WHEN UF.UPPER_CLASS = 1 THEN 'U' " .
              "WHEN UF.LOWER_CLASS = 1 THEN 'L' " .
              "END AS UPP " .
              "FROM UFSPC0052T UF " .
              "WHERE UF.LBL_BARCODE = sc.bld_lvl_cd " .
            "AND ROWNUM = 1)) UPP " .
          "FROM TSRQA0006T SC, TSRQA0003T QA " .
          "WHERE sc.syori_ts >= TO_DATE(TO_CHAR(sysdate-1,'YYYY-MM-DD')||' 08:00:00','YYYY-MM-DD HH24:MI:SS') " .
          "AND sc.syori_ts < TO_DATE(TO_CHAR(sysdate,'YYYY-MM-DD')||' 08:00:00','YYYY-MM-DD HH24:MI:SS') " .
          "AND sc.item_cd = qa.item_cd(+) " .
          "ORDER BY SC.syori_ts) " .
        "GROUP BY  ART, UPP " .
        "ORDER BY CNT DESC) REP " .
        "WHERE ROWNUM < 6";
	
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

// Procution result PM
$app->get('/api/productionpm', function(Request $request, Response $response){
	
	$conn = oci_connect('TSRB', 'TSRB', '10.102.1.22/FX01');
	$statement = "SELECT TO_CHAR(TRUNC(SYSDATE),'YYYY/MM/DD')||' (D), '|| CURE.CNT ||'(PC_CURE), '||KURA.CNT ||'(PC_WH), '||KURAA.CNT ||'(PC(RE)_WH), ".
					"'||TBCURE.CNT ||'(TB_CURE), '||TBKURA.CNT ||'(TB_WH)' AS RESULT ".
					"FROM ".
					"(SELECT COUNT(*) CNT FROM TSRBE0511T  WHERE CURE_END_TS >= TO_DATE(TO_CHAR(sysdate,'YYYY-MM-DD')||' 08:00:00','YYYY-MM-DD HH24:MI:SS') ".
					"AND CURE_END_TS <= TO_DATE(TO_CHAR(sysdate,'YYYY-MM-DD')||' 20:00:00','YYYY-MM-DD HH24:MI:SS')) CURE, ".
					"(SELECT COUNT(*) CNT FROM TSRKA2002TT WHERE JSK_TS >= TO_DATE(TO_CHAR(sysdate,'YYYY-MM-DD')||' 08:00:00','YYYY-MM-DD HH24:MI:SS') ".
					"AND JSK_TS < TO_DATE(TO_CHAR(sysdate,'YYYY-MM-DD')||' 20:00:00','YYYY-MM-DD HH24:MI:SS') ".
					"AND SEIBAN IS NOT NULL ".
					"AND return_kubun is null) KURA, ".
					"(SELECT COUNT(*) CNT FROM TSRKA2002TT WHERE JSK_TS >= TO_DATE(TO_CHAR(sysdate,'YYYY-MM-DD')||' 08:00:00','YYYY-MM-DD HH24:MI:SS') ".
					"AND JSK_TS < TO_DATE(TO_CHAR(sysdate,'YYYY-MM-DD')||' 20:00:00','YYYY-MM-DD HH24:MI:SS') ".
					"AND SEIBAN IS NOT NULL ". 
					"AND return_kubun is not null) KURAA, ".
					"(SELECT COUNT(*) CNT FROM TSRBE0402T WHERE CURE_END_TS >= TO_DATE(TO_CHAR(sysdate,'YYYY-MM-DD')||' 08:00:00','YYYY-MM-DD HH24:MI:SS') ".
					"AND CURE_END_TS <= TO_DATE(TO_CHAR(sysdate,'YYYY-MM-DD')||' 20:00:00','YYYY-MM-DD HH24:MI:SS')) TBCURE, ".
					"(SELECT COUNT(*) CNT FROM TSRKA2002T WHERE JSK_TS >= TO_DATE(TO_CHAR(sysdate,'YYYY-MM-DD')||' 08:00:00','YYYY-MM-DD HH24:MI:SS') ".
					"AND JSK_TS <= TO_DATE(TO_CHAR(sysdate,'YYYY-MM-DD')||' 20:00:00','YYYY-MM-DD HH24:MI:SS') ".
					"AND SEIBAN IS NOT NULL) TBKURA";
	
    
	if (!$conn) {
        $e = oci_error();
        trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
    }
    else {
        $stid = oci_parse($conn, $statement);
        oci_execute($stid);

		$rows = array();
		while($r = oci_fetch_assoc($stid)) {
			$rows[] = $r;
		}
		echo json_encode($rows);
    }
	oci_close($conn);
});

// Procution result scrap PC/LT PM
$app->get('/api/scrappcpm', function(Request $request, Response $response){
	
	$conn = oci_connect('TSRB', 'TSRB', '10.102.1.22/FX01');
	$statement = "SELECT (REP.CNT ||'('|| ".
         "REP.ART ||'('|| ".
         "REP.UPP ||'))') AS ROW1 FROM ".
        "(SELECT ART, UPP, COUNT(*) CNT FROM ".
          "(SELECT SC.ART_NO ART, sc.bld_lvl_cd LBL,  CONCAT(NVL(qa.item_mei, sc.item_cd), ".
            "(SELECT ".
              "CASE  ".
              "WHEN UF.STATIC_CLASS = 1 THEN 'S' ".
              "WHEN UF.COUPLE_CLASS = 1 THEN 'C' ".
              "WHEN UF.UPPER_CLASS = 1 THEN 'U' ".
              "WHEN UF.LOWER_CLASS = 1 THEN 'L' ".
              "END AS UPP ".
              "FROM UFSPC0052T UF ".
              "WHERE UF.LBL_BARCODE = sc.bld_lvl_cd ".
            "AND ROWNUM = 1)) UPP ".
          "FROM TSRQA0006TT SC, TSRQA0003T QA ".
          "WHERE sc.syori_ts >= TO_DATE(TO_CHAR(sysdate,'YYYY-MM-DD')||' 08:00:00','YYYY-MM-DD HH24:MI:SS') ".
          "AND sc.syori_ts < TO_DATE(TO_CHAR(sysdate,'YYYY-MM-DD')||' 20:00:00','YYYY-MM-DD HH24:MI:SS') ".
          "AND sc.item_cd = qa.item_cd(+) ".
          "ORDER BY SC.syori_ts) ".
        "GROUP BY  ART, UPP ".
        "ORDER BY CNT DESC) REP ".
        "WHERE ROWNUM < 6";
	
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

// Procution result scrap TBS PM
$app->get('/api/scraptbpm', function(Request $request, Response $response){
	
	$conn = oci_connect('TSRB', 'TSRB', '10.102.1.22/FX01');
	$statement = "SELECT  (REP.CNT ||'('|| ".
         "REP.ART ||'('|| ".
         "REP.UPP ||'))') AS ROW1 FROM ".
        "(SELECT ART, UPP, COUNT(*) CNT FROM ".
          "(SELECT SC.ART_NO ART, sc.bld_lvl_cd LBL,  CONCAT(NVL(qa.item_mei, sc.item_cd), ".
            "(SELECT ".
              "CASE ".
              "WHEN UF.STATIC_CLASS = 1 THEN 'S' ".
              "WHEN UF.COUPLE_CLASS = 1 THEN 'C' ".
              "WHEN UF.UPPER_CLASS = 1 THEN 'U' ".
              "WHEN UF.LOWER_CLASS = 1 THEN 'L' ".
              "END AS UPP ".
              "FROM UFSPC0052T UF ".
              "WHERE UF.LBL_BARCODE = sc.bld_lvl_cd ".
            "AND ROWNUM = 1)) UPP ".
          "FROM TSRQA0006T SC, TSRQA0003T QA ".
          "WHERE sc.syori_ts >= TO_DATE(TO_CHAR(sysdate,'YYYY-MM-DD')||' 08:00:00','YYYY-MM-DD HH24:MI:SS') ".
          "AND sc.syori_ts < TO_DATE(TO_CHAR(sysdate,'YYYY-MM-DD')||' 20:00:00','YYYY-MM-DD HH24:MI:SS') ".
          "AND sc.item_cd = qa.item_cd(+) ".
          "ORDER BY SC.syori_ts) ".
        "GROUP BY  ART, UPP ".
        "ORDER BY CNT DESC) REP ".
        "WHERE ROWNUM < 6";
	
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



// *** JUH *** //
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
// *** JUH *** //

// *** Plant Control *** //

$app->post('/pc/lastproduction', function(Request $request, Response $response){
     //TB Cure TSRBE0402T
	//PC Cure TSRBE0511T
	$last = $request->getbody();
	//$art = explode(",", $last);

	//echo json_encode($art);
	$dataarray = json_decode($last, true);
	$arrlength = count($dataarray);
	$sql = array();
	// echo $arrlength ."\n";
	//echo $last ."\n"; 
	//echo $dataarray . "\n" ;
	for ($x = 0; $x < $arrlength; $x++) {
		//echo $art[1];
		// echo substr($dataarray[6], 1, 1);
		if ( substr($dataarray[$x], 0, 1) == 'B' ) {
			$sql[] = "select to_char(max(cure_end_ts), 'YYYY-MM-DD HH24:MI') AS LAST_DATE, ART_NO from tsrx.tsrbe0402t where cure_end_ts > (sysdate-365) and art_no = '".$dataarray[$x]."' group by ART_NO";
		} else {
			$sql[] = "select to_char(max(cure_end_ts), 'YYYY-MM-DD HH24:MI') AS LAST_DATE, ART_NO from tsrx.tsrbe0511t where cure_end_ts > (sysdate-365) and art_no = '".$dataarray[$x]."' group by ART_NO";
		}

	}
	$sqlcount = count($sql);
	//echo $sqlcount;
 	$conn = oci_connect('TSRB', 'TSRB', '10.102.1.22/FX01');
 	$jsonData = array();

	for ($y = 0; $y < $sqlcount; $y++) {
		if (!$conn) {
			$e = oci_error();
			trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
		}
		else {
			$stid = oci_parse($conn, $sql[$y]);
			oci_execute($stid);

			while($row = oci_fetch_assoc($stid)) {
				$jsonData[] = $row;
			}
		}
		oci_close($conn);
	}
	echo json_encode($jsonData);	

});


$app->get('/pc/lastproduce/{art}', function(Request $request, Response $response){
	//TB Cure TSRBE0402T
   //PC Cure TSRBE0511T
   $last = $request->getAttribute('art');
   //$art = explode(",", $last);

   //echo json_encode($art);
   //$dataarray = json_decode($last, true);
   //$arrlength = count($dataarray);
   //$sql = array();
   // echo $arrlength ."\n";
   //echo $last ."\n"; 
   //echo $dataarray . "\n" ;
   //for ($x = 0; $x < $arrlength; $x++) {
	   //echo $art[1];
	   // echo substr($dataarray[6], 1, 1);
	if ( substr($last, 0, 1) == 'B' ) {
		$sql = "select to_char(max(cure_end_ts), 'YYYY-MM-DD HH24:MI') AS LAST_DATE, ART_NO from tsrx.tsrbe0402t where cure_end_ts > (sysdate-365) and art_no = '".$last."' group by ART_NO";
	} else {
		$sql = "select to_char(max(cure_end_ts), 'YYYY-MM-DD HH24:MI') AS LAST_DATE, ART_NO from tsrx.tsrbe0511t where cure_end_ts > (sysdate-365) and art_no = '".$last."' group by ART_NO";
	}

   //}
   //$sqlcount = count($sql);
   //echo $sqlcount;
	$conn = oci_connect('TSRB', 'TSRB', '10.102.1.22/FX01');
	//$jsonData = array();

   //for ($y = 0; $y < $sqlcount; $y++) {
	try {
	   if (!$conn) {
		   $e = oci_error();
		   trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
	   }
	   else {
		   $stid = oci_parse($conn, $sql);
		   oci_execute($stid);
			$Data = oci_fetch_assoc($stid);
		//    while($row = oci_fetch_assoc($stid)) {
		// 	   $Data = $row;
		//    }
	   }
	   oci_close($conn);
	   echo json_encode($Data);
	}
	catch (Exception $e) {
		echo json_encode($e);
	}
   //}
   
   	

});

// *** Plant Control *** //

// *** PR Record *** //
$app->put('/pr/prinputrecord', function(Request $request, Response $response){

	$data = $request->getbody();
	$dataarray = json_decode($data, true);
	$arrlength = count($dataarray);
	//echo $arrlength;
	
	$PR_NUMBER = $dataarray['prno'];
	$PR_COSTCENTER = $dataarray['costcenter'];
	$PR_GL_NUMBER = $dataarray['glcost'];
	$PR_DATE = $dataarray['prdate'];
	$PR_ITEM_DETAIL = $dataarray['itemdesc'];
	$PR_UNIT_PRICE = $dataarray['unitprice'];
	$PR_QTY = $dataarray['qty'];
	$PR_UNIT_NAME = $dataarray['unit'];
	$PR_AMOUNT_PRICE = $dataarray['amountprice'];
	$PR_SUPPLIER_NAME = $dataarray['suppliername'];
	$PR_DUE_DATE = $dataarray['duedate'];
	$PR_REMARK = $dataarray['remark'];


	$sql = "INSERT INTO ITDB.PR_Record (PR_NUMBER, PR_COSTCENTER, PR_GL_NUMBER, PR_DATE, PR_ITEM_DETAIL, PR_UNIT_PRICE, PR_QTY, PR_UNIT_NAME, PR_AMOUNT_PRICE, PR_SUPPLIER_NAME, PR_DUE_DATE, PR_REMARK) VALUES ".
		"(:PR_NUMBER, :PR_COSTCENTER, :PR_GL_NUMBER, :PR_DATE, :PR_ITEM_DETAIL, :PR_UNIT_PRICE, :PR_QTY, :PR_UNIT_NAME, :PR_AMOUNT_PRICE, :PR_SUPPLIER_NAME, :PR_DUE_DATE, :PR_REMARK)";
	
	try {
		$db = new itdb();
		$db = $db->connect();
		$stmt = $db->prepare($sql);

		$stmt->bindParam(':PR_NUMBER', $PR_NUMBER);
		$stmt->bindParam(':PR_COSTCENTER', $PR_COSTCENTER);
		$stmt->bindParam(':PR_GL_NUMBER', $PR_GL_NUMBER);
		$stmt->bindParam(':PR_DATE', $PR_DATE);
		$stmt->bindParam(':PR_ITEM_DETAIL', $PR_ITEM_DETAIL);
		$stmt->bindParam(':PR_UNIT_PRICE', $PR_UNIT_PRICE);
		$stmt->bindParam(':PR_QTY', $PR_QTY);
		$stmt->bindParam(':PR_UNIT_NAME', $PR_UNIT_NAME);
		$stmt->bindParam(':PR_AMOUNT_PRICE', $PR_AMOUNT_PRICE);
		$stmt->bindParam(':PR_SUPPLIER_NAME', $PR_SUPPLIER_NAME);
		$stmt->bindParam(':PR_DUE_DATE', $PR_DUE_DATE);
		$stmt->bindParam(':PR_REMARK', $PR_REMARK);
		$stmt->execute();
		echo '{"notice": {"text": "Data Added"}}';
	}
	catch (PDOException $e){
		echo '{"error": {"text": '.$e->getMessage().'}}';
	}
});
// *** PR Record *** //