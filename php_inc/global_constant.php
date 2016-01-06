<?php
	//	contains global constants such as folder structures
	//for page rendering 
	include_once 'server_cred.php';
	define("SHOP_DIR", ROOTDIR.'shop/');
	define("MEDDIR",ROOTDIR."media/");
	define("JS_PATH", ROOTDIR.'js/');
	define("PHP_INC_PATH",ROOTDIR.'php_inc/');
	define("ADMIN_PAGE", ROOTDIR.'admin');
	
	define("MODEL_PATH", PHP_INC_PATH.'model/');
	define("ADMIN_PRODUCT_ADDNEW", ADMIN_PAGE.'/product/addnew');
	define("ADMIN_PRODUCT_SELLING", ADMIN_PAGE.'/product/selling');
	define("ADMIN_PRODUCT_ORDER", ADMIN_PAGE.'/order');
	
	
	
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
	define("ORDER_CONFIRM_PAGE", ROOTDIR.'shop/order/confirm/');
	define("ADMIN_LOGIN", ADMIN_PAGE.'/login');
	define("ADMIN_LOGOUT",ADMIN_PAGE.'/logout' );
	
?>