<?php
		include_once '../php_inc/core.inc.php';
		unset($_SESSION['admin_id']);
		header('location:'.ADMIN_LOGIN);
?>