<?php 
	spl_autoload_register(function($className){
		$srcPath = scandir("./vendor/firebase/php-jwt/src");
		foreach ($srcPath as $subdirClass) {
			if ($subdirClass != "." && $subdirClass != "..") {
				require_once("./vendor/firebase/php-jwt/src/".$subdirClass);
			}
		}
		$path = strtolower($className) . ".php";
		if(file_exists($path)) {
			require_once($path);
		} else {
			echo "File $path is not found.";
		}
	})

 ?>