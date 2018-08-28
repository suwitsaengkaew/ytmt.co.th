<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app = new \Slim\App(['settings' => ['displayErrorDetails' => true]]);

//Get All Customers
$app->get('/api/docuprint', function(Request $request, Response $response){
    $sql = "SELECT * FROM cas_sdr_history AS sdr
		    LEFT JOIN cat_validation AS au ON au.id=sdr.accountid
            LEFT JOIN cat_device AS sd ON sdr.srcdevid=sd.id
            LEFT JOIN cat_device AS td ON sdr.tgtdevid=td.id 
            LEFT JOIN cas_user_ext AS u ON au.id=u.x_id
            LEFT JOIN cat_transaction AS t ON sdr.trxguid=t.trxguid
            LEFT JOIN cas_location] AS l ON td.locationid=l.id
            LEFT JOIN (select dga.devid grdevid,dgrp.name,dgrp.type from cas_device_group_assoc dga, cas_device_group dgrp where dga.id=dgrp.id and dgrp.type='arb') dg on dg.grdevid=td.id";

    try {
        $db = new sqldb();
        $db = $db->connect();

        $stmt = $db->query($sql);
        $docuprint = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($docuprint);

    } catch (PDOException $e){ 
        echo '{"error": {"text": '.$e->getMessage().'}}';
    }
});
