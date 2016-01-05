<?php
	include_once 'php_inc/core.inc.php';
	$product = new Product();
	$products_list = $product->adminProductSellingRenderer();
	if(!isset($_GET['st'])){
		$_GET['st'] = 'selling';
	}
	include_once 'phtml/admin.phtml';	
?>