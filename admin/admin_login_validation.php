<?php
	include_once '../php_inc/core.inc.php';
	$admin_cre = new Admin_Credential();
	 
	 if(isset($_POST['username'],$_POST['password'] ) && !empty($_POST['username']) && !empty($_POST['password'])  )
	 
	 
	 if($admin_cre->login($_POST['username'], $_POST['password'])){
	 	header('location:'.ADMIN_PRODUCT_SELLING);
	 }else{
	 	die('login invalid');
	 }
	



?>