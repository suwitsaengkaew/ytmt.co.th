<?php
header("Content-Type: text/html; charset=utf-8");
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app = new \Slim\App(['settings' => ['displayErrorDetails' => true]]);

// **********************************************************
// * MySQL DataBase	
// **********************************************************
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