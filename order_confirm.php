<?php
	include_once 'php_inc/core.inc.php';
	$order_record = new Order_Record();
	if(isset($_GET['id'])){
		echo $order_record->printOrderConfirmationPgae($_GET['id']);
	}else{
		header('location:'.ROOTDIR);
	}
?>