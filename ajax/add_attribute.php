<?php
	include_once '../php_inc/core.inc.php';
	if(isset($_POST["product_id"]) && !empty($_POST["product_id"]) && isset($_POST["name"]) && !empty($_POST["name"]) && isset($_POST["quantity"]) && !empty($_POST["quantity"]) && isset($_POST["size"]) && !empty($_POST["size"]) && isset($_POST["cover"]) && !empty($_POST["cover"]) && isset($_POST['uploadable']) && !empty($_POST['uploadable'])  ){
		if($_POST['uploadable'] == 'true'){
			if(isset($_FILES['pic'])){
				$validator = new Media_Validation();
				if(!$validator->isValidImageFile($_FILES['pic']) || !$validator->isValidImageSize($_FILES["pic"])){
					echo '-1'; //invalid media file
					exit();	
				}
			}else{
				echo '-1';
				exit();
			}
		}else{
			$_FILES['pic'] = NULL;
		}
		$atr = new Product_Attribute();
		$atr->addAttr($_POST["product_id"], $_POST["name"], $_POST["quantity"], $_POST["size"], $_POST["cover"],$_POST['uploadable'], $_FILES["pic"]);
	}else{
		echo '1';
	}

	
?>