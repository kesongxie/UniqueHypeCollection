<?php
	include_once '../php_inc/core.inc.php';
	if(isset($_POST['title']) && !empty($_POST['title']) && isset($_POST['description']) && !empty($_POST['description']) && isset($_POST['price']) && !empty($_POST['price'])  ){
		$product = new Product();
		echo ($product->addNewProduct($_POST['title'], $_POST['description'], $_POST['price']) === false)?'1':'0';
	}else{
		echo '1';
	}
?>