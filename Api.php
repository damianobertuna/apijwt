<?php
class Api extends RestJwt {

    public function __construct() {
        parent::__construct();
    }

    public function actionLogin() {
        // get username and password from request
        $request    = json_decode($this->request);
        $username   = $request->param->username;
        $password   = $request->param->password;

        if ($username == "" || $password == "") {
            throw new Exception($this->throwError(INVALID_USER_PASS, 'Invalid user or password'));
        } 
        
        // check in the database if user is present

        // check if user and password are correct
        $usernameFromDb = "test@email.com";
        $passwordFromDb = "mypassword";
        if ($username != $usernameFromDb || $password != $passwordFromDb) {
            throw new Exception($this->throwError(INVALID_USER_PASS, 'Invalid user or password'));
        }
        
        // create and return token JWT
        var_dump($username);
        var_dump($password);
        http_response_code(200);
        return true;
    }

}