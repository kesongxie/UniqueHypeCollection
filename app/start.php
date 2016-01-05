<?php
	include_once  $_SERVER['DOCUMENT_ROOT'].'/php_inc/core.inc.php';
	include_once  $_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php';
	
	$apiContext = new \PayPal\Rest\ApiContext(
		new \PayPal\Auth\OAuthTokenCredential(
		"AWtSw5bGIUmSu1wrI8MikeMMq3LEYtMh-IPDmVvDYDjOSabnVX9IOG48hmiJjmVF2W_HUYWeVZYCKSqG",
		"ED5DE3nPUfl07djhhm5gGEVxarDbL8zrk0j-ppkq-JdQ5pxl6dT03d1VbJP1ollFpk8DorFTGpU1QYAa"
		)
	);
	
	
	
	
	
	


?>