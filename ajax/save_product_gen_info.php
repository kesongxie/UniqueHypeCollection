<?php
	include_once '../php_inc/core.inc.php';
	if(isset($_POST['product_id']) && !empty($_POST['product_id']) && isset($_POST['title']) && !empty($_POST['title']) && isset($_POST['desc']) && !empty($_POST['desc'])  && isset($_POST['price']) && !empty($_POST['price'])){
		$product = new Product();
		$product->saveProductGeneralInfo($_POST['product_id'], $_POST['title'], $_POST['desc'], $_POST['price']);
	}
	
	
	
?>