<?php
	include_once 'app/start.php';	
	use PayPal\Api\Payment;
	use PayPal\Api\PaymentExecution;
	
	
	if(!isset($_GET['success'], $_GET['paymentId'],$_GET['PayerID'])){
		die();
	}
	
	if((bool)$_GET['success'] === false){
		die();
	}
	
	
	
	$paymentId = $_GET['paymentId'];
	$payerId = $_GET['PayerID'];
	
	$payment = Payment::get($paymentId, $apiContext);
	$execute = new PaymentExecution();
	$execute->setPayerId($payerId);
	
	
	try{
		$result = $payment->execute($execute, $apiContext);
	}catch(Exception $e){
		die($e);
	}
	
	var_dump_pre($result);
	
	
	//echo 'Payment made. Thanks.. directing to the thank you page'; //direct to the user 
	
	//thanso the user can't see the details of the id
	
	
?>