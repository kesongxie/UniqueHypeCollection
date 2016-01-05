<?php
	include_once '../php_inc/core.inc.php';
	if(isset($_POST['attribute_row_id']) && !empty($_POST['attribute_row_id']) && isset($_POST['product_id']) && !empty($_POST['product_id'])){
		$atr = new Product_Attribute();
		$atr->updateCoverByRowId($_POST['attribute_row_id'], $_POST['product_id']);
	}
?>