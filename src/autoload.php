<?php 
	spl_autoload_register(function($className){
		$srcPaths[] = './vendor/firebase/php-jwt/src/';
		$srcPaths[] = './vendor/guzzlehttp/guzzle/src/';
		$srcPaths[] = './vendor/guzzlehttp/guzzle/src/Cookie/';
		$srcPaths[] = './vendor/guzzlehttp/guzzle/src/Exception/';
		$srcPaths[] = './vendor/guzzlehttp/guzzle/src/Handler/';
		$srcPaths[] = './class/';
		$srcPaths[] = './config/';

		foreach ($srcPaths as $srcPath) {
			$srcPathFiles = scandir($srcPath);
			foreach ($srcPathFiles as $subdirClass) {
				// per src di guzzle bisogna far saltare le cartelle
				// quindi subdirClass deve contenere .php
				if ($subdirClass != "." && $subdirClass != "..") {
					require_once($srcPath.$subdirClass);
				}
			}
		}
	})

 ?>