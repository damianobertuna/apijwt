<?php 

/*Data Type*/
define('BOOLEAN', 	'1');
define('INTEGER', 	'2');
define('STRING', 	'3');

/*Error Codes*/
define('REQUEST_METHOD_NOT_VALID',		        100);
define('REQUEST_CONTENTTYPE_NOT_VALID',	        101);
define('REQUEST_NOT_VALID', 			        102);
define('VALIDATE_PARAMETER_REQUIRED', 			103);
define('VALIDATE_PARAMETER_DATATYPE', 			104);
define('API_NAME_REQUIRED', 					105);
define('API_PARAM_REQUIRED', 					106);
define('API_METHOD_INVALID', 					107);
define('INVALID_USER_PASS', 					400);
define('LOGIN_FAILED',		 					401);
define('UNAUTHORIZED',		 					401);
define('USER_NOT_ACTIVE', 						109);

define('SUCCESS_RESPONSE', 						200);

/*Server Errors*/

define('JWT_PROCESSING_ERROR',					300);
define('ATHORIZATION_HEADER_NOT_FOUND',			301);
define('ACCESS_TOKEN_ERRORS',					302);

define('UPDATE_ERROR',							500);
define('GENERIC_ERROR',							500);

define('DATABASE_CONNECTION_FAILED',			503);

?>