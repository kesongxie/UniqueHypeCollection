<?php
	include_once '../php_inc/core.inc.php';
	if(isset($_POST["product_id"]) && !empty($_POST["product_id"]) && isset($_POST["attribute_name"]) && !empty($_POST["attribute_name"])  ){
		$atr = new Product_Attribute();
		$result = $atr->getAttributePhotoForProduct($_POST["product_id"], $_POST["attribute_name"]);
		echo  ($result !== false)?$result:'1';
	}else{
		echo  '1';
	}

	
?>