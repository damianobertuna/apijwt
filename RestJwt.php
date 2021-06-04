<?php

include_once("config.php");
use Firebase\JWT\JWT;

class RestJwt {

    protected $actionName;
    protected $resource;
    protected $request;
    protected $responseCode;
    protected $responseMessage;
    protected $jwtToken;
    protected $jwtRefreshToken;

    public function __construct() 
    {
        /*if($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->throwError(REQUEST_METHOD_NOT_VALID, 'Request Method is not valid.');
        }*/
        $handler = fopen('php://input', 'r');
        $this->request = stream_get_contents($handler);
        $this->validateRequest();

        /*$db = new DbConnect;
        $this->dbConn = $db->connect();*/

        /*if( 'generatetoken' != strtolower( $this->actionName) ) {
            $this->validateToken();
        }*/
    }

    /**
     * This method validate the request checking the json structure
     * in order to verify the format
     */
    public function validateRequest() 
    {
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
        return true;
    }

    /**
     * Token validation
     */
    public function validateToken() 
    {        
        try {
            $this->jwtToken = $this->getBearerToken();
		    $payload = JWT::decode($this->jwtToken, SECRETE_KEY, ['HS256']);
        } catch (Exception $e) {
            /* before launching an exception we verify the refresh token */
            if ($_COOKIE["JwtRefreshToken"]) {
                $this->jwtRefreshToken = $_COOKIE["JwtRefreshToken"];
                /**
                 * Before try to decode the JWT refresh token we need to verify on the database 
                 * if it is still valid
                 */
                if ($this->validateRefreshToken()) {
                    $payload = JWT::decode($this->jwtRefreshToken, SECRETE_KEY, ['HS256']);
                    $newJwtToken = $this->generateToken();
                    $this->jwtToken = $newJwtToken;
                    return true;
                }    
            }
            throw new Exception($this->throwError(UNAUTHORIZED, $e->getMessage()));
        }
        return true;
    }

    /**
     * Method to validate the JWT refresh token
     */
    public function validateRefreshToken() {
        /* check in the database if the JWT refresh token is present */
        /* $db->checkRefreshToken($this->jwtRefreshToken);*/
        if ($this->jwtRefreshToken != "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE2MjI4MzkwNTgsImlzcyI6ImxvY2FsaG9zdCIsImV4cCI6MTYyMzQ0Mzg1OH0.JxYUEI-sacAXu6Ik2ErgIwLaSVz79jyKqNy9DfwoJnk") {
            return false;
        }
        return true;
    }

    /**
     * Get the token from the request header
     */
    public function getBearerToken() 
    {
        $headers = $this->getAuthorizationHeader();
        
        if (!empty($headers)) {
            if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
                return $matches[1];
            }
        }

        throw new Exception($this->throwError(ATHORIZATION_HEADER_NOT_FOUND, 'Access Token Not found'));
    }

    /**
     * Get the header from the request
     */
    public function getAuthorizationHeader()
    {
        $headers = null;
        if (isset($_SERVER['Authorization'])) {
            $headers = trim($_SERVER["Authorization"]);
        }
        else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
            $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
        } elseif (function_exists('apache_request_headers')) {
            $requestHeaders = apache_request_headers();
            // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
            $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
            if (isset($requestHeaders['Authorization'])) {
                $headers = trim($requestHeaders['Authorization']);
            }
        }
        return $headers;
    }

    /**
     * Entry method for the API
     */
    public function processApi() 
    {
        try {    
            $rMethod = new reflectionMethod('API', $this->serviceName);
            if(!method_exists($this, $this->serviceName)) {
                $this->throwError(API_DOST_NOT_EXIST, "API does not exist.");
            }
            $rMethod->invoke($this);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }   
    }

    /**
     * Method that generates a JWT token
     * @param int $time
     */
    public function generateToken(int $time=60) 
    {
        try {
            $paylod = [
                'iat' => time(),
                'iss' => 'localhost',
                'exp' => time() + ($time),                
            ];
            $token = JWT::encode($paylod, SECRETE_KEY);
            return $token;            
        } catch (Exception $e) {
            throw new Exception($this->throwError(JWT_PROCESSING_ERROR, $e->getMessage()));
        }
    }

    /**
     * Throw error utility
     */
    public function throwError(int $code, string $message) 
    {
        return json_encode(['error' => ['status'=>$code, 'message'=>$message]]);
    }

    /**
     * Get response utility
     */
    public function getResponse() 
    {
        return json_encode(['response' => ['status'=>$this->responseCode, 'message'=>$this->responseMessage, 'token' => $this->jwtToken, 'resource' => $this->resource]]);
    }

}