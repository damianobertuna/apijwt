<?php

use Firebase\JWT\JWT;

class RestJwt {

    protected $actionName;
    protected $resource;
    protected $request;
    protected $responseCode;
    protected $responseMessage;
    protected $jwtToken;
    protected $jwtRefreshToken;
    protected $databaseObj;
    protected $responseObj;

    /**
     * @param Response $responseObj
     */
    public function __construct(Response $responseObj) 
    {
        $this->databaseObj = new Database();
        $this->responseObj = $responseObj;

        if(isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->responseObj->setStatus(REQUEST_METHOD_NOT_VALID);
            throw new Exception($this->getErrorMessage('ivalid_method'));
        }

        $handler = fopen('php://input', 'r');
        $this->request = stream_get_contents($handler);
        $this->validateRequest();
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
            $this->responseObj->setStatus(API_NAME_REQUIRED);
            throw new Exception($this->getErrorMessage('api_name_required'));
        }
        
        $this->serviceName = $data['name'];

        if(!is_array($data['param'])) {
            $this->responseObj->setStatus(API_PARAM_REQUIRED);
            throw new Exception($this->getErrorMessage('api_param_required'));            
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
            if (array_key_exists("JwtRefreshToken", $_COOKIE)) {
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
            $this->responseObj->setStatus(UNAUTHORIZED);
            throw new Exception($e->getMessage());            
        }
        return true;
    }

    //JwtRefreshToken=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE2MjI4ODcwMDUsImlzcyI6ImxvY2FsaG9zdCIsImV4cCI6MTYyMzQ5MTgwNX0.yw4oLQ2pXriJbuJ-n9bOUecPciIYMZDydS4ZGdIOir0; expires=Thu, 08-Jan-1970 00:00:00 GMT; Max-Age=0; HttpOnly

    /**
     * Method to validate the JWT refresh token
     */
    public function validateRefreshToken() {
        /* check in the database if the JWT refresh token is present */
        $dbJwtRefreshToken = $this->databaseObj->getRefreshToken($this->jwtRefreshToken);
        if (!$dbJwtRefreshToken || $this->jwtRefreshToken != $dbJwtRefreshToken->token) {
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

        $this->responseObj->setStatus(ATHORIZATION_HEADER_NOT_FOUND);
        throw new Exception($this->getErrorMessage('token_not_found'));
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
                $this->responseObj->setStatus(API_METHOD_INVALID);
                throw new Exception($this->getErrorMessage('api_method_invalid'));                  
            }
            $rMethod->invoke($this);
        } catch (ReflectionException $re) {
            $this->responseObj->setStatus(API_METHOD_INVALID);
            throw new Exception($this->getErrorMessage('api_method_invalid'));  
        } catch (Exception $e) {
            $this->responseObj->setStatus(GENERIC_ERROR);
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
            $this->responseObj->setStatus(JWT_PROCESSING_ERROR);
            throw new Exception($e->getMessage());            
        }
    }

    /**
     * Get response utility
     */
    public function getResponse() 
    {
        return json_encode(['response' => ['status'=>$this->responseCode, 'message'=>$this->responseMessage, 'token' => $this->jwtToken, 'resource' => $this->resource]]);
    }

    /**
     * @param string $errorName
     */
    public function getErrorMessage(string $errorName) {
        $errorDictionary = array(
            'invalid_user_password'         => 'Invalid user or password',
            'user_not_found'                => 'User not found',
            'ivalid_method'                 => 'Request Method is not valid',
            'api_name_required'             => 'API name is required.',
            'api_param_required'            => 'API param is required',
            'api_password_required'         => 'API password param is required',
            'api_username_required'         => 'API username param is required',
            'api_old_password_required'     => 'API param old password is required',
            'api_new_password_required'     => 'API param new password is required',
            'api_method_invalid'            => 'API does not exist',
            'token_not_found'               => 'Access Token Not found',
            'password_update_error'         => 'Password update failed'
        );
        return $errorDictionary[$errorName];
    }

}