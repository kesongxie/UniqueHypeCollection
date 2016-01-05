<?php
	include_once '../php_inc/core.inc.php';
	if(isset($_POST['key']) && !empty($_POST['key']) && isset($_POST['size']) && !empty($_POST['size']) && isset($_POST['atr_name_key']) && !empty($_POST['atr_name_key'])){
		$atr = new Product_Attribute();
		$inventory = $atr->isInStock($_POST['key'], $_POST['size'], $_POST['atr_name_key']);
		if($inventory !== false){
			echo $inventory;
		}else{
			echo '-1';
		}
	}
	
	
	
?>