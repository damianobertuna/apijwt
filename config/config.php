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


    /* Database connection data */
    /*$user       = "root";
    $password   = "1234qwer";
    $dbname     = "apijwt";
    $host       = "localhost";*/


*/