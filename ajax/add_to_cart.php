<?php
	include_once '../php_inc/core.inc.php';
	if(isset($_POST['key'], $_POST['atr_name_key'], $_POST['size']) && !empty($_POST['key']) && !empty($_POST['atr_name_key']) && !empty($_POST['size']) ){
		$atr = new Product_Attribute();
		echo ($atr->addToCart($_POST['key'], $_POST['atr_name_key'], $_POST['size']))?'0':'1';
	}else{
		echo '1';
	}
?>