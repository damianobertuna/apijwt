<?php 

/*Error Codes*/
define('REQUEST_METHOD_NOT_VALID',		        400);
define('REQUEST_CONTENTTYPE_NOT_VALID',	        400);
define('API_NAME_REQUIRED', 					400);
define('API_PARAM_REQUIRED', 					400);
define('API_METHOD_INVALID', 					400);
define('INVALID_USER_PASS', 					400);
define('UNAUTHORIZED',		 					401);

define('SUCCESS_RESPONSE', 						200);

/*Server Errors*/

define('JWT_PROCESSING_ERROR',					500);
define('ATHORIZATION_HEADER_NOT_FOUND',			400);

define('UPDATE_ERROR',							500);
define('GENERIC_ERROR',							500);

define('DATABASE_CONNECTION_FAILED',			503);

//define('REQUEST_NOT_VALID', 			        400);
//define('VALIDATE_PARAMETER_REQUIRED', 			400);
//define('VALIDATE_PARAMETER_DATATYPE', 			400);
//define('ACCESS_TOKEN_ERRORS',					400);
//define('LOGIN_FAILED',		 					401);
//define('USER_NOT_ACTIVE', 						109);
/*Data Type*/
/*define('BOOLEAN', 	'1');
define('INTEGER', 	'2');
define('STRING', 	'3');*/

?>