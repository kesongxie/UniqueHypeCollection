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
	// echo 'this is the payment id from checkout.php'.$_SESSION['payment_id'].'<br>';
// 	echo 'this is the payment id from finish_checkout.php'.$_GET['paymentId'];
// 	
	$paymentId = $_GET['paymentId'];
	$payerId = $_GET['PayerID'];
	
	
	
		
	
	$payment = Payment::get($paymentId, $apiContext);
	$execute = new PaymentExecution();
	$execute->setPayerId($payerId);
	
	
	//paymentid = PAY-5RK4927837679442PK2GB5HA
	//atr_key
	
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
	
	
	/*
		1 Main St
		San Jose, CA 95131
		United States
	*/
	$s_adr = $line1.', '.$city.', '.$state.' '.$postalCode.' '.$country_code;
	
	

 	$transactions = $result->getTransactions();

 	
	$order_record = new Order_Record();
	$order_item_record = new Order_Item_Record();

	foreach($transactions as $transaction){
		$ItemList = $transaction->getItemList();
		$order_record_id = $order_record->insertRecord($payer_firstname,$payer_lastname,  $email, $phone, $s_adr, date('Y-m-d H:i:s'));
		if($order_record_id !== false){
			$items_array = $ItemList->getItems();
			foreach($items_array as $item){
				$total = $item->getPrice()*$item->getQuantity();
				$item_description = $item->getName().' / '.$item->getDescription().' / '.getCurrencyFormat($total);
				$order_item_record->insertItemRecord($order_record_id, $item_description, $item->getUrl(), $total);		
			}
		}
	}
	
	
	var_dump_pre($result);	
	//echo 'Payment made. Thanks.. directing to the thank you page'; //direct to the user 
	
	//thanso the user can't see the details of the id
	
	
?>