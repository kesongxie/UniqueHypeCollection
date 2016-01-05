<?php
	include_once '../php_inc/core.inc.php';
	if(isset($_POST['product_id']) && !empty($_POST['product_id']) && isset($_POST['name']) && !empty($_POST['name']) && isset($_POST['size']) && !empty($_POST['size'])){
		$atr = new Product_Attribute();
		echo $atr->isAttributeNameAndSizeAlreadyExisted($_POST['product_id'], $_POST['name'], $_POST['size'])?'1':'0';
	}else{
		echo '1';
	}
	
	
	
?>