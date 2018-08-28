<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app = new \Slim\App(['settings' => ['displayErrorDetails' => true]]);

//Get All registrationId
$app->get('/api/pushmessage', function(Request $request, Response $response){
    $sql = "SELECT * FROM push_registrationId";

    try {
        $db = new db();
        $db = $db->connect();

        $stmt = $db->query($sql);
        $push_registrationId = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;

        echo json_encode($push_registrationId);
    } catch (PDOException $e) {
         echo '{"error": {"text": '.$e->getMessage().'}}';
    }
});


//Post registrationId
$app->post('/api/pushmessage/add', function(Request $request, Response $response) {
    $registrationId = $request->getParam('registrationId');
    
    $sqlQuery = "SELECT * FROM push_registrationId WHERE registrationId = :registrationId";
    try {
        $db = new db();
        $db = $db->connect();

        $stmt = $db->prepare($sqlQuery);
        $stmt->bindParam(':registrationId',$registrationId);
        $stmt->execute();
        //$findId = $stmt->fetchAll(PDO::FETCH_NUM);
        $rowCount = $stmt->fetchAll(PDO::FETCH_NUM);
        if ($rowCount[0] < 1) {
            $sql = "INSERT INTO push_registrationId (registrationId) VALUES (:registrationId)";
            try {
                $db = new db();
                $db = $db->connect();
                $stmt = $db->prepare($sql);
                $stmt->bindParam(':registrationId', $registrationId);
                $stmt->execute();
                echo '{ "notice" : { "text" : "Data was Added"}} -> '.$rowCount[0];
            } catch (PDOException $e) {
                echo '{"error": {"text": '.$e->getMessage().'}}';
            }
        }
        else {
            echo '{ "notice" : { "text" : "Data already existing"}}'.$rowCount[0];
        }
        $db = null;
    } catch (PDOException $e){ 
        echo '{"error": {"text": '.$e->getMessage().'}}';
    }    
});