<?php
class Api extends RestJwt {

    /**
     * @param Response $responseObj
     */
    public function __construct(Response $responseObj) 
    {
        parent::__construct($responseObj);
    }

    /* login example json
       {
          "name": "actionLogin",
          "param": {
              "username": "test@email.com",
              "password": "mypassword"
          }
       }
    */
    /**
     * Method to handle login action
     */
    public function actionLogin() 
    {
        /* Verify that params provided are correct */
        try {                        
            $request = $this->validateObj->validateActionLoginParam();            
        } catch (Exception $e) {
            throw new Exception($this->getErrorMessage($e->getMessage()));
        }
                        
        $username   = $request->param->username;        
        $password   = $request->param->password;
        
        if ($username == "" || $password == "") {
            $this->responseObj->setStatus(INVALID_USER_PASS);
            throw new Exception($this->getErrorMessage('invalid_user_password'));
        } 
        
        // check in the database if user is present
        $userData = $this->databaseObj->getUser($username, $password);
        if (!$userData) {
            $this->responseObj->setStatus(UNAUTHORIZED);
            throw new Exception($this->getErrorMessage('user_not_found'));
        }

        // check if user and password are correct
        if ($username != $userData->username || $password != $userData->password) {
            $this->responseObj->setStatus(INVALID_USER_PASS);
            throw new Exception($this->getErrorMessage('invalid_user_password'));            
        }
        
        // create and return JWT token and JWT refresh token
        $this->responseObj->setStatus(SUCCESS_RESPONSE);
        $this->responseObj->setMessage("Login success");
        $this->responseObj->setToken($this->generateToken());
        $this->jwtRefreshToken  = $this->generateToken(60*60*24*7); /* 604800 seconds = 7 days */
        setcookie("JwtRefreshToken", $this->jwtRefreshToken, 604800, "", "", false, true); /* last true param is for HTTPONLY */
        /* save jwtRefreshToken to database */
        $this->databaseObj->saveRefreshToken($this->jwtRefreshToken, $userData->id);
        return true;
    }

    /*  get resource example json
        {
           "name": "actionGetResource",
           "param": {
               "id": "resource_id"
           }
        }
    */
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
            /**
             * Dummy code for testing purpose only. It's necessary to verify the user profile in
             * order to be sure that this action is allowed
             */
            $this->responseObj->setStatus(SUCCESS_RESPONSE);
            $this->responseObj->setMessage("Get resource allowed");
            $this->responseObj->setToken($this->jwtToken);
            /* before sending the resource we need to check if the user has permission */
            $this->responseObj->setResource('Here is the resource requested');            
        }

        /* get the resource from the database */
    }
        
    
    /*  password change example json         
        {
           "name": "actionGetResource",
           "param": {
               "username": "test@email.com",
               "oldPassword": "mypassword",
               "newPassword": "mypassword"
           }
        }
    */
    /**
     * Method for password changing
     */ 
    public function actionChangePassword() {
        /* Verify that params provided are correct */
        try {                        
            $request = $this->validateObj->validateChangePasswordParam();            
        } catch (Exception $e) {
            throw new Exception($this->getErrorMessage($e->getMessage()));
        }

        if ($this->validateToken()) {
            $username       = $request->param->username;
            $oldPassword    = $request->param->oldPassword;
            $newPassword    = $request->param->newPassword;

            // check in the database if user is present
            $userData       = $this->databaseObj->getUser($username, $oldPassword);
            if (!$userData) {
                $this->responseObj->setStatus(UNAUTHORIZED);
                throw new Exception($this->getErrorMessage('user_not_found'));
            }

            $updateResult = $this->databaseObj->changePassword($username, $oldPassword, $newPassword);
            if ($updateResult) {
                $this->responseObj->setStatus(SUCCESS_RESPONSE);
                $this->responseObj->setMessage("Password updated");
                $this->responseObj->setToken($this->jwtToken);
                return true;
            }

            $this->responseObj->setStatus(UPDATE_ERROR);
            throw new Exception($this->getErrorMessage('password_update_error'));
        }
    }
}