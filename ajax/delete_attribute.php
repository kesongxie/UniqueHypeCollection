<?php
	include_once '../php_inc/core.inc.php';
	if(isset($_POST['data_attribute_row_id']) && !empty($_POST['data_attribute_row_id'])){
		$atr = new Product_Attribute();
		$atr->deleteAttr($_POST['data_attribute_row_id']);
	}
	
	
	
?>