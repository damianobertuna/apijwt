<?php

include_once("config.php");

class RestJwt {

    protected $actionName;
    public $request;

    public function __construct() {
        /*if($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->throwError(REQUEST_METHOD_NOT_VALID, 'Request Method is not valid.');
        }*/
        $handler = fopen('php://input', 'r');
        $this->request = stream_get_contents($handler);
        $this->validateRequest();

        /*$db = new DbConnect;
        $this->dbConn = $db->connect();*/

        if( 'generatetoken' != strtolower( $this->actionName) ) {
            $this->validateToken();
        }
    }

    public function validateRequest() {
        /*if($_SERVER['CONTENT_TYPE'] !== 'application/json') {
            $this->throwError(REQUEST_CONTENTTYPE_NOT_VALID, 'Request content type is not valid');
        }*/

        $data = json_decode($this->request, true);

        if(!isset($data['name']) || $data['name'] == "") {
            $this->throwError(API_NAME_REQUIRED, "API name is required.");
        }
        
        $this->serviceName = $data['name'];

        if(!is_array($data['param'])) {
            $this->throwError(API_PARAM_REQUIRED, "API PARAM is required.");
        }

        $this->param = $data['param'];
    }

    public function validateToken() {

    }

    public function processApi() {
        try {
            $api = new API;
            $rMethod = new reflectionMethod('API', $this->serviceName);
            if(!method_exists($api, $this->serviceName)) {
                $this->throwError(API_DOST_NOT_EXIST, "API does not exist.");
            }
            $rMethod->invoke($api);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }   
    }

    public function throwError($code, $message) {        
        $errorMsg = json_encode(['error' => ['status'=>$code, 'message'=>$message]]);
        return $errorMsg;
    }

}