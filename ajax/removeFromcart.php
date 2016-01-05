<?php
	include_once '../php_inc/core.inc.php';
	if(isset($_POST["atr_key"]) && !empty($_POST["atr_key"]) ){
		$atr = new Product_Attribute();
		$atr->removeFromCart($_POST["atr_key"]);
	}else{
		echo  '1';
	}

	
?>