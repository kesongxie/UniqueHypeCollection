<?php
	include_once '../php_inc/core.inc.php';
	$admin_cre = new Admin_Credential();
	 
	 if(isset($_POST['username'],$_POST['password'], $_POST['re-password'] ) && !empty($_POST['username'])  && !empty($_POST['password'])  && !empty($_POST['re-password'])  )
	 
	 
	 if($_POST['password'] != $_POST['re-password']  ){
	 	die();
	 }
	 
	 if($admin_cre->createNewAdmin($_POST['username'], $_POST['password'])){
	 	header('location:'.ADMIN_LOGIN);
	 }else{
	 	die();
	 }
	



?>