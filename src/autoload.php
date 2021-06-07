<?php 
require_once('./config/config.php');
require_once('./config/globals.php');

spl_autoload_register(function($className){
	$srcPaths[] = './vendor/firebase/php-jwt/src/';
	$srcPaths[] = './vendor/guzzlehttp/guzzle/src/';
	$srcPaths[] = './vendor/psr/http-client/src/';
	$srcPaths[] = './class/';

	foreach ($srcPaths as $srcPath) {
		/**
		 * if the classes are in the form Firebase\JWT\JWT
		 * we take only the last class name JWT fot instance
		 */
		$classNameTmp = explode("\\", $className);
		if (count($classNameTmp) > 0) {
			$className = $classNameTmp[count($classNameTmp)-1];
		}
		
		if (file_exists($srcPath.$className.'.php')) {
			require_once($srcPath.$className.'.php');
			return;
		}
	}
})

?>