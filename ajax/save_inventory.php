<?php
	include_once '../php_inc/core.inc.php';
	if(isset($_POST['attribute_row_id']) && !empty($_POST['attribute_row_id'])){
		$atr = new Product_Attribute();
		echo ($atr->updateInventory($_POST['attribute_row_id'], $_POST['inventory']) !== false)?'0':'1';
	}else{
		echo '1';
	}
?>