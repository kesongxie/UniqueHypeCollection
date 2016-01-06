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
	
	if($_GET['paymentId'] != $_SESSION['payment_id']){
		die();
	}
	
	$paymentId = $_GET['paymentId'];
	$payerId = $_GET['PayerID'];
	
	//check inventory
	$payment = Payment::get($paymentId, $apiContext);
	$execute = new PaymentExecution();
	$execute->setPayerId($payerId);
	
	try{
		$result = $payment->execute($execute, $apiContext);
	}catch(Exception $e){
		die($e);
	}
	
	$payer_info = $result->getPayer()->getPayerInfo();
	$payer_firstname = $payer_info->getFirstName();
	$payer_lastname = $payer_info->getLastName();
	$customer = $payer_firstname.' '.$payer_lastname;
	$phone = $payer_info->getPhone();
	$shipping_address = $payer_info->getShippingAddress();
	$email = $payer_info->getEmail();
	$recipient_name = $shipping_address->getRecipientName();
	$line1 = $shipping_address->getLine1();
	$city = $shipping_address->getCity();
	$state = $shipping_address->getState();
	$postalCode = $shipping_address->getPostalCode();
	$country_code = $shipping_address->getCountryCode();
	$s_adr = $line1.', '.$city.', '.$state.' '.$postalCode.' '.$country_code;
 	$transactions = $result->getTransactions();
	$order_record = new Order_Record();

	foreach($transactions as $transaction){
		$ItemList = $transaction->getItemList();
		$order_record_id = $order_record->insertRecord($paymentId, $payer_firstname,$payer_lastname,  $email, $phone, $s_adr, date('Y-m-d H:i:s'));
	}
	
	//print confirmation page
	header('location:'.ORDER_CONFIRM_PAGE.$paymentId);
	
	
	
	
	
?>