<?php 
	include_once 'php_inc/core.inc.php';

	$product = new Product();
	$products_list = $product->productHomeRenderer();
	include_once 'phtml/index.phtml';	
	
	
?>