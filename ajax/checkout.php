<?php
	include_once '../app/start.php';
	use PayPal\Api\Payer;
	use PayPal\Api\Item;
	use PayPal\Api\ItemList;
	use PayPal\Api\Details;
	use PayPal\Api\Amount;
	use PayPal\Api\Transaction;
	use PayPal\Api\RedirectUrls;
	use PayPal\Api\Payment;

	
	
	if(!isset($_POST['data'])){
		die();
	}
	
	$atr = new Product_Attribute();
	$item_array = array();
	$shipping = 0;
	$sub_total = 0;
	$total = 0;
	foreach($_POST['data'] as $product){
		$product_info = $atr->getCheckoutProductInfoWithAttributes($product['atr_key'],  $product['quantity']);
		if($product_info !== false){
			$item = new Item();
			$item->setName($product_info['title'])
			->setCurrency('USD')
			->setQuantity($product['quantity'])
			->setDescription($product_info['attribute_name']." / ".$product_info['size'])
			->setUrl($product_info['url'])
			->setPrice($product_info['price']);
			$sub_total += $product_info['price'] * $product['quantity'];
			array_push($item_array, $item);
				
		}
	}
	

	$payer = new Payer();
	$payer->setPaymentMethod('paypal');
	
	
	$itemList = new ItemList();
	$itemList->setItems($item_array);
	
	$details = new Details();
	$details->setShipping($shipping)
			->setSubtotal($sub_total);
	
	$total = $sub_total + $shipping;
	
	$amount = new Amount();
	$amount->setCurrency('USD')
			->setTotal($total)
			->setDetails($details);
	
	$transaction = new Transaction();
	$transaction->setAmount($amount)
				->setItemList($itemList)
				->setDescription('Unique Hype Collection Transaction')
				->setInvoiceNumber(uniqid());
				
	$redirectUrls = new RedirectUrls();			
	$redirectUrls->setReturnUrl(AFTER_PAYMENT_REDIRECT.'?success=true')
				 ->setCancelUrl(AFTER_PAYMENT_REDIRECT.'?success=false');
				
	$payment = new Payment();
	$payment->setIntent('sale')
			->setPayer($payer)
			->setRedirectUrls($redirectUrls)
			->setTransactions([$transaction]);
	
	try {
   		$payment->create($apiContext);
	} catch (PayPal\Exception\PayPalConnectionException $ex) {
		echo $ex->getCode(); // Prints the Error Code
		echo $ex->getData(); // Prints the detailed error message 
		die($ex);
	} catch (Exception $ex) {
		die($ex);
	}
	
	
	
	
	$approvalUrl = $payment->getApprovalLink();
	deleteCookie('cart_items');
	
	$payment_item_row = new Payment_Item_Row();
	$payment_id = $payment->getId();
	$_SESSION['payment_id'] = $payment_id;
	
	foreach($_POST['data'] as $product){
		$total = $product['quantity'] * $atr->getProductPriceByProductAttributeId($product['atr_key']);
		$product_info = $payment_item_row->insertPaymentItemRow($payment_id, $product['atr_key'], $product['quantity'], $total);
		$inventory = $atr->getInventoryByProductAttributeId($product['atr_key']) - $product['quantity'];
		if($inventory < 0){
			$inventory = 0;
		}
		$atr->updateInventory($product['atr_key'], $inventory);
	}
	echo $approvalUrl;
?>