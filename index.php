<?php
header("Access-Control-Allow-Origin: *");
header("Content-type: application/json; charset=UTF-8");
require_once('src/autoload.php');

$responseObj = new Response;

try {		
	$api = new Api($responseObj);
	$api->processApi();
	echo $api->getResponse();
} catch (PDOException $pdo) {
	echo $pdo->getMessage();
} catch (Exception $e) {
	$responseObj->setMessage($e->getMessage());		
	http_response_code($responseObj->getStatus());
	echo $responseObj->toJson();
}