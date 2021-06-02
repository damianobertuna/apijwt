<?php
	header("content-type: application/json");    
	require_once('src/autoload.php');

	try {
		$api = new Api;
		$response = $api->processApi();
	} catch (Exception $e) {
		$response = json_decode($e->getMessage());
		http_response_code($response->error->status);
		echo $e->getMessage();
	}

?>