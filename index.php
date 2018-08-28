<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, PUT, POST, DELETE");
header("Access-Control-Allow-Headers: X-Custom-Header");
//header("Access-Control-Allow-Headers: Range");

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require './vendor/autoload.php';
require './src/config/db.php'; //Connect to MySQL SERVER 10.102.2.33
//require './src/config/sql.php'; //Connect to MS SQL SERVER RPT00024

$app = new \Slim\App();
//$app->contentType('application/json;charset=utf-8');

$app->get('/hello/{name}', function (Request $request, Response $response) {
    $name = $request->getAttribute('name');
    $response->getBody()->write("Hello, $name");

    return $response;
});

//Customer Routes
//require './src/routes/customers.php';
//require './src/routes/pushmessage.php';
require './src/routes/routing.php';
//require './src/routed/oracle.php';
//require './src/routes/docuprint.php';

$app->run();