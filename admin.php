<?php
	include_once 'php_inc/core.inc.php';
	$product = new Product();
	$products_list = $product->adminProductSellingRenderer();
	
	include_once 'phtml/admin.phtml';	
?>