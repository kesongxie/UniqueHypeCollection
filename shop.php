<?php
	include_once 'php_inc/core.inc.php';
	
	$product_preview = '';
	if($_GET['url']){
		$product =  new Product();
		$atr = new Product_Attribute();
		$product_title =  $product->getProductTitleByUrl($_GET['url']);
		$product_preview = $product->loadProductInfoByUrl($_GET['url']);
		$cart_item_num = $atr->getCartItemNum();
		include_once 'phtml/shop.phtml';		
	}else{
		//404
	}
	
	
	
	
?>