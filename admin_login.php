<?php
	include_once 'php_inc/core.inc.php';
	$admin_cre = new Admin_Credential();
	 
	if(isset($_SESSION['admin_id'])){
		header('location:'.ADMIN_PRODUCT_SELLING);
	}
	echo $admin_cre->loadAdminLoginBody();
	
?>