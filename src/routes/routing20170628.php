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

$app->get('/acdb/article', function(Request $request, Response $response){
	
	//$id = $request->getAttribute('id');
	$sql = "select ARTICLE, ARTNAME  from BOMARTICLE";
	
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

$app->get('/acdb/artspec/{prefix}', function(Request $request, Response $response){
	
	$prefix = $request->getAttribute('prefix');
	$sql = "SELECT ARTICLE,	CC5126, CC5127, CC5173, CC5291, CC5305, CC5368, UT5315, WT5363, CT5033, CT5135,
  			CT5341, CT5246, CT5360, CT5387, CT5142, CT5200, CT5025, CT5131, CT5323, CT5056, CT5353, CT5296,
  			JL5673, JLLM2670, JLLM2650, BTTB12200, BTTB11003, BTTB06500, BT5688, BT5511, ET5688, ET5511, BE5062,
  			BS5239, RC5618, BNS5017, BUS5239, BLS5239, WR5026, WR5045, 3RC5618, BC5062, BSW5239, WUS5625, PC5625,
  			LE2651, LE5754, LE2749, LE5749, LE6748, FIL5625, WK11200, BEAD5517, BF5665, BEAD5693, CHF5419,CHFWNN0143,
  			CHF5618, 1IL5634, 1IL5508, NRF5474, NRFWLM4836 FROM ACDB.BOMSPEC where SUBSTR(ARTICLE ,1,1) = '$prefix'";
	
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

$app->get('/acdb/rmkind', function(Request $request, Response $response){
	
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


// *** JUH *** //