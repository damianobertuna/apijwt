<?php 
require_once('./config/config.php');
require_once('./config/globals.php');

spl_autoload_register(function($className){
	$srcPaths[] = './vendor/firebase/php-jwt/src/';
	$srcPaths[] = './vendor/guzzlehttp/guzzle/src/';
	$srcPaths[] = './vendor/psr/http-client/src/';
	/*$srcPaths[] = './vendor/guzzlehttp/guzzle/src/Cookie/';
	$srcPaths[] = './vendor/guzzlehttp/guzzle/src/Exception/';
	$srcPaths[] = './vendor/guzzlehttp/guzzle/src/Handler/';*/
	$srcPaths[] = './class/';

	foreach ($srcPaths as $srcPath) {
		$classNameTmp = explode("\\", $className);
		if (count($classNameTmp) > 0) {
			$className = $classNameTmp[count($classNameTmp)-1];
		}
		
		if (file_exists($srcPath.$className.'.php')) {
			require_once($srcPath.$className.'.php');
			return;
		}			
		/*$srcPathFiles = scandir($srcPath);
		foreach ($srcPathFiles as $subdirClass) {
			// per src di guzzle bisogna far saltare le cartelle
			// quindi subdirClass deve contenere .php
			if ($subdirClass != "." && $subdirClass != "..") {
				if (strpos($subdirClass, ".php")) {
					require_once($srcPath.$subdirClass);
				} else {
					$srcPaths[] = $srcPath.$subdirClass."/";
				}
			}
		}*/
	}
})

?>