<?php
	//	contains global constants such as folder structures
	//for page rendering 
	include_once 'server_cred.php';
	define("SHOP_DIR", ROOTDIR.'shop/');
	define("MEDDIR",ROOTDIR."media/");
	define("JS_PATH", ROOTDIR.'js/');
	define("PHP_INC_PATH",ROOTDIR.'php_inc/');
	define("MODEL_PATH", PHP_INC_PATH.'model/');
	

	
	define("ADMIN_PRODUCT_ADDNEW", ROOTDIR.'admin/product/addnew');
	define("ADMIN_PRODUCT_SELLING", ROOTDIR.'admin/product/selling');
	define("EDIT_SELLIING_PATH",ADMIN_PRODUCT_SELLING.'/edit/');
	define("MAXIMUM_UPLOAD_IMAGE_SIZE", 6242880);
	
	
	define("DOCUMENT_ROOT", $_SERVER['DOCUMENT_ROOT'].'/');
	define("TEMPLATE_PATH", DOCUMENT_ROOT.'phtml/');
	define("UPLOAD_MEDIA_DIR",	DOCUMENT_ROOT.'media/' );
	define("MEDIA_THUMBNAIL_PREFIX", "thumb_");
	
	define("DEFAULT_IMAGE", MEDDIR.'supreme-default.jpg');	
	define("CART_DELIMITER", ',');
	
	define("AFTER_PAYMENT_REDIRECT", ROOTDIR.'finish_checkout.php');
	define("SHOPPING_BAG_VIEW_PATH", ROOTDIR.'shop/bag/view');
	
?>