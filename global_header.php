<?php
	include_once 'php_inc/core.inc.php';
	$atr = new Product_Attribute();
	$cart_item_num = $atr->getCartItemNum();
	include_once TEMPLATE_PATH.'global_header.phtml';
?>