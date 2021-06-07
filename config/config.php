<?php


/*Security*/
define('SECRETE_KEY', 'KEaWrHUFG5*$WIivjIzZ0Cg*');


/*

    public function validateRequest() 
    {
        /*if($_SERVER['CONTENT_TYPE'] !== 'application/json') {
            $this->throwError(REQUEST_CONTENTTYPE_NOT_VALID, 'Request content type is not valid');
        }*/

        /*$data = json_decode($this->request, true);

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

    $user       = "root";
    $password   = "1234qwer";
    $dbname     = "apijwt";
    $host       = "localhost";
*/

/* check if param username exists */
        /*if (!is_object($request->param) || !property_exists($request->param, 'username')) {
            $this->responseObj->setStatus(API_PARAM_REQUIRED);
            throw new Exception($this->getErrorMessage('api_username_required'));            
        }*/
        /* check if param password exists */
        /*if (!is_object($request->param) || !property_exists($request->param, 'password')) {
            $this->responseObj->setStatus(API_PARAM_REQUIRED);
            throw new Exception($this->getErrorMessage('api_password_required'));            
        }*/
        
// get username and password from request
            //$request        = json_decode($this->request);
            /* check if param username exists */
            /*if (!is_object($request->param) || !property_exists($request->param, 'username')) {
                $this->responseObj->setStatus(API_PARAM_REQUIRED);
                throw new Exception($this->getErrorMessage('api_username_required'));            
            }*/
            

            /* check if param oldPassword exists */
            /*if (!is_object($request->param) || !property_exists($request->param, 'oldPassword')) {
                $this->responseObj->setStatus(API_PARAM_REQUIRED);
                throw new Exception($this->getErrorMessage('api_old_password_required'));            
            }*/
            /* check if param newPassword exists */
            /*if (!is_object($request->param) || !property_exists($request->param, 'newPassword') || $request->param->newPassword == '') {
                $this->responseObj->setStatus(API_PARAM_REQUIRED);
                throw new Exception($this->getErrorMessage('api_new_password_required'));            
            }*/