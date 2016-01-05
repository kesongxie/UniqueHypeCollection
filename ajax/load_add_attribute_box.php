<?php
	include_once '../php_inc/core.inc.php';
	$product_size = new Product_Size(); 
	ob_start();
	include 'phtml/load_add_attribute_box.phtml';
	$content =  ob_get_clean();
	echo $content;
	
?>