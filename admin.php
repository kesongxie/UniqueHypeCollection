<?php
	include_once 'php_inc/core.inc.php';
	$product = new Product();
	
	if(!isset($_SESSION['admin_id'])){
		header('location:'.ADMIN_LOGIN);
	}
	
	$products_list = $product->adminProductSellingRenderer();
	include_once 'phtml/admin.phtml';	
?>