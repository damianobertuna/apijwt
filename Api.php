<?php
class Api extends RestJwt {

    public function __construct() 
    {
        parent::__construct();
    }

    /**
     * Method to handle login action
     * 
     * request json example
     * {
     *      "name": "actionLogin",
     *      "param": {
     *          "username": "test@email.com",
     *          "password": "mypassword"
     *      }
     *  }
     */
    public function actionLogin() 
    {
        // get username and password from request
        $request    = json_decode($this->request);
        $username   = $request->param->username;
        $password   = $request->param->password;

        if ($username == "" || $password == "") {
            throw new Exception($this->throwError(INVALID_USER_PASS, 'Invalid user or password'));
        } 
        
        // check in the database if user is present
        /* $db->getUser($username, $password); */

        // check if user and password are correct
        $usernameFromDb = "test@email.com";
        $passwordFromDb = "mypassword";
        if ($username != $usernameFromDb || $password != $passwordFromDb) {
            throw new Exception($this->throwError(INVALID_USER_PASS, 'Invalid user or password'));
        }
        
        // create and return JWT token and JWT refresh token        
        $this->responseCode     = SUCCESS_RESPONSE;
        $this->responseMessage  = "Login success";
        $this->jwtToken         = $this->generateToken();
        $this->jwtRefreshToken  = $this->generateToken(60*60*24*7); /* 604800 seconds = 7 days */
        setcookie("JwtRefreshToken", $this->jwtRefreshToken, 604800, "", "", false, true); /* last true param is for HTTPONLY */
        /* save jwtRefreshToken to database */
        /* $db->saveRefreshToken($this->jwtRefreshToken); */
        return true;
    }

    /**
     * Method to handle get resource action
     * 
     * request json example     
     * 
     * Header example
     * Authorization Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE2MjI4MDI5NzcsImlzcyI6ImxvY2FsaG9zdCIsImV4cCI6MTYyMjgwMzAzN30.HQpahDGJJsPL-shtZ5YOrZb0RTdt_6TfDcFQoiNF5tA
     * Cookie form Postman JwtRefreshToken=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE2MjI4MzkwNTgsImlzcyI6ImxvY2FsaG9zdCIsImV4cCI6MTYyMzQ0Mzg1OH0.JxYUEI-sacAXu6Ik2ErgIwLaSVz79jyKqNy9DfwoJnk; HttpOnly; Path=/; Expires=Sat, 04 Jun 2022 20:48:15 GMT;
     */
    public function actionGetResource() {
        /* verify token */
        if ($this->validateToken()) {
            $this->responseCode     = SUCCESS_RESPONSE;
            $this->responseMessage  = "Valid resource request";            
            /* before sending the resource we need to check if the user has permission */
            $this->resource = "Here is the resource requested";
        }

        /* get the resource from the database */
    }
    /* 
        {
           "name": "actionGetResource",
           "param": {
               "id": "resource_id"
           }
        }
    */

}