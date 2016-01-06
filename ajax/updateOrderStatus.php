<?php
	include_once '../php_inc/core.inc.php';
	if(isset($_POST['order_record_id']) && isset($_POST['order_status']) && !empty($_POST['order_record_id']) && !empty($_POST['order_status'])){
		$order_record = new Order_Record();
		$order_record->updateOrderStatus($_POST['order_record_id'], $_POST['order_status'] );
	}
	
	
?>