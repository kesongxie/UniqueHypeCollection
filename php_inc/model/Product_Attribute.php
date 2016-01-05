<?php
	include_once 'Core_Table.php';
	class Product_Attribute extends Core_Table{
		private $table_name = 'product_attribute';
		
		public function __construct(){
			parent::__construct($this->table_name);
		}
		
		
		public function getProductCoverPathByProductId($product_id){
			$stmt = $this->connection->prepare("SELECT `shot_path` FROM `$this->table_name` WHERE `product_id` = ?  AND  `cover` = '1' LIMIT 1 ");
			if($stmt){
				$stmt->bind_param('i', $product_id);
				if($stmt->execute()){
					 $result = $stmt->get_result();
					 if($result !== false && $result->num_rows == 1){
						$row = $result->fetch_assoc();
						$stmt->close();
						return MEDDIR.$row['shot_path'];
					 }
				}
			}
			
			return DEFAULT_IMAGE;
		}
		
		public function getProductSnapShotCollectionByProductId($product_id){
			$stmt = $this->connection->prepare("SELECT DISTINCT `attribute_name_id`, `shot_path`, `cover` FROM `$this->table_name` WHERE `product_id` = ? ORDER BY  `cover` DESC ");
			if($stmt){
				$stmt->bind_param('i', $product_id);
				if($stmt->execute()){
					 $result = $stmt->get_result();
					 if($result !== false && $result->num_rows >= 1){
						$rows = $result->fetch_all(MYSQLI_ASSOC);
						$stmt->close();
						return $rows;
					 }
				}
			}
			echo $this->connection->error;
			return false;
		}
		
		
		public function getProductAttributeCollectionByProductId($product_id){
			$stmt = $this->connection->prepare("SELECT * FROM `$this->table_name` WHERE `product_id` = ? ORDER BY  `cover` DESC,  `attribute_name_id` ASC ");
			if($stmt){
				$stmt->bind_param('i', $product_id);
				if($stmt->execute()){
					 $result = $stmt->get_result();
					 if($result !== false && $result->num_rows >= 1){
						$rows = $result->fetch_all(MYSQLI_ASSOC);
						$stmt->close();
						return $rows;
					 }
				}
			}
			echo $this->connection->error;
			return false;
		}
		
		
		
		
		public function getProductAvailableSizeCollectionByProductId($product_id){
			$stmt = $this->connection->prepare("SELECT DISTINCT `product_size_id` FROM `$this->table_name` WHERE `product_id` = ? ORDER BY  `product_size_id`  ");
			if($stmt){
				$stmt->bind_param('i', $product_id);
				if($stmt->execute()){
					 $result = $stmt->get_result();
					 if($result !== false && $result->num_rows >= 1){
						$rows = $result->fetch_all(MYSQLI_ASSOC);
						$stmt->close();
						return $rows;
					 }
				}
			}
			echo $this->connection->error;
			return false;
		}
		
		
		
		
		
		public function isInStock($product_id, $size, $attribute_name_id){
			$product_size = new Product_Size();
			$product_size_id = $product_size->getProductSizeIdBySizeText($size);
			if($product_size_id !== false){
				//there is eligible size selected
				$stmt = $this->connection->prepare("SELECT `inventory` FROM `$this->table_name` WHERE `product_id` = ? AND  `attribute_name_id` = ? AND `product_size_id` = '$product_size_id' ");
			}else{
				$stmt = $this->connection->prepare("SELECT SUM(inventory) as 'inventory' FROM `$this->table_name` WHERE `product_id` = ? AND  `attribute_name_id` = ? ");
			}
			if($stmt){
				$stmt->bind_param('ii', $product_id, $attribute_name_id);
				if($stmt->execute()){
					 $result = $stmt->get_result();
					 if($result !== false && $result->num_rows == 1){
						$row = $result->fetch_assoc();
						$stmt->close();
						return intval($row['inventory']) > 0 ? $row['inventory']:false;
					 }
				}
			}
			echo $this->connection->error;
			return false;
		}
		
		public function updateInventory($attribute_row_id, $inventory){
			if(is_numeric($inventory) && validatesAsInt($inventory) ){
				$this->setColumnById('inventory', $inventory, $attribute_row_id );
				return true;
			}
			return false;
		}
	
		
		public function updateCoverByRowId($product_attribute_id, $product_id){
			$attribute_name_id = $this->getColumnBySelector('attribute_name_id', 'product_attribute_id', $product_attribute_id);
			if($attribute_name_id !== false){
				$stmt = $this->connection->prepare("UPDATE `$this->table_name` SET `cover` = '0' WHERE `product_id`= ?");
				if($stmt){
					$stmt->bind_param('i', $product_id);
					if($stmt->execute()){
						$stmt = $this->connection->prepare("UPDATE `$this->table_name` SET `cover` = '1' WHERE `product_id`= ? AND `attribute_name_id` = ? ");
						if($stmt){
							$stmt->bind_param('ii', $product_id, $attribute_name_id);
							if($stmt->execute()){
								$stmt->close();
								return true;
							}
							echo $this->connection->error;
						}					
					}			
				}
			}
			
			return false;
		}
		
		public function getProductIdByProductAttributeId($product_attrubute_id){
			return $this->getColumnById('product_id',$product_attrubute_id);
		}
		
		public function getAttributeNameIdByProductAttributeId($product_attrubute_id){
			return $this->getColumnById('attribute_name_id',$product_attrubute_id);
		}
		
		public function getShotPathByProductAttributeId($product_attrubute_id){
			return $this->getColumnById('shot_path',$product_attrubute_id);
		}
		
		public function isAttributeExistsForProduct($product_id){
			return $this->isNumericValueExistingForColumn($product_id, 'product_id') !== false;
		}
		
		
		public function deleteAttr($product_attrubute_id){
			$product_id = $this->getProductIdByProductAttributeId($product_attrubute_id);
			$attribute_name_id = $this->getAttributeNameIdByProductAttributeId($product_attrubute_id);
			if($product_id !== false){
				$num = $this->getNumberForGivenAttributeOfProduct($product_id, $attribute_name_id);	
				if($num == 1){
					//delete the picture as well.
					$shot_path = $this->getShotPathByProductAttributeId($product_attrubute_id);
					$f_m = new File_Manager();
					$foler_path = UPLOAD_MEDIA_DIR.explode('/',$shot_path)[0];
					$f_m->rrmdir($foler_path);
				}	
				$this->deleteRowById($product_attrubute_id);
			}
		}
		
		
		
		
		public function isAttributeNameAndSizeAlreadyExisted($product_id, $attribute_name, $size_text){
			$at_n = new Attribute_Name();
			$size = new Product_Size();
			$attribute_name_id = $at_n->getAttributeIdFromName($attribute_name);
			if($attribute_name_id === false){
				return false;
			}
			$product_size_id = $size->getProductSizeIdBySizeText($size_text);
			if($product_size_id !== false){
				return $this->isAttributeForProductExists($product_id, $attribute_name_id, $product_size_id);
			}
		}
		
		
		
		
		
		public function isAttributeForProductExists($product_id, $attribute_name_id, $product_size_id){
			$stmt = $this->connection->prepare("SELECT `product_attribute_id` FROM `$this->table_name` WHERE `product_id` = ? AND `attribute_name_id` = ? AND `product_size_id` = ? LIMIT 1");
			if($stmt){
				$stmt->bind_param('iii', $product_id, $attribute_name_id,$product_size_id );
				if($stmt->execute()){
					 $result = $stmt->get_result();
					 if($result->num_rows == 1){
					 	$stmt->close();
						return true;
					 }
				}
			}
			echo $this->connection->error;
			return false;
		}
		
		
		
		
		public function isAttributeNameIdForProductExists($product_id, $attribute_name_id){
			$stmt = $this->connection->prepare("SELECT `product_attribute_id` FROM `$this->table_name` WHERE `product_id` = ? AND `attribute_name_id` = ? LIMIT 1");
			if($stmt){
				$stmt->bind_param('ii', $product_id, $attribute_name_id );
				if($stmt->execute()){
					 $result = $stmt->get_result();
					 if($result->num_rows == 1){
					 	$stmt->close();
						return true;
					 }
				}
			}
			echo $this->connection->error;
			return false;
		}
		
		
		public function isGivenAttributeSetAsCover($product_id, $attribute_name_id){
			$stmt = $this->connection->prepare("SELECT `cover` FROM `$this->table_name` WHERE `product_id` = ? AND `attribute_name_id` = ? LIMIT 1");
			if($stmt){
				$stmt->bind_param('ii', $product_id, $attribute_name_id );
				if($stmt->execute()){
					 $result = $stmt->get_result();
					 if($result->num_rows == 1){
					 	$stmt->close();
					 	$row = $result->fetch_assoc();
						return $row['cover'] == '1';
					 }
				}
			}
			echo $this->connection->error;
			return false;
		}
			
		
		
		
		public function addAttr($product_id, $attribute_name, $inventory, $size_text, $isCover, $uploadable, $file){
			$at_n = new Attribute_Name();
			$product = new Product();
			$size = new Product_Size();
			
			$attribute_name_id = $at_n->getAttributeIdFromName($attribute_name);
			if($attribute_name_id == false){
				$attribute_name_id = $at_n->addNewAttribute($attribute_name);
			}
			
			$product_size_id = $size->getProductSizeIdBySizeText($size_text);
			if($attribute_name_id === false || !is_numeric($inventory) || !validatesAsInt($inventory) || $product_size_id === false || $product->isRowExists($product_id)){
				return false;
			}
			if($this->isAttributeForProductExists($product_id, $attribute_name_id, $product_size_id) === false){
				//not exists
				if($uploadable == 'true'){
					//upload new one
					$f_m = new File_Manager();
					$shot_path = $f_m->upload_File_To_Dir($file);	
				}else{
					//retrieve the old one
					$shot_path = $this->getAttributePhotoForProduct($product_id, $attribute_name, false);
				}
				if($shot_path !== false){
					$stmt = $this->connection->prepare("INSERT INTO `$this->table_name` (`product_id`, `attribute_name_id`, `product_size_id`, `shot_path`, `inventory`) VALUES (?, ?, ?, ?, ?) ");
					if($stmt){
						$stmt->bind_param('iiisi', $product_id, $attribute_name_id, $product_size_id, $shot_path, $inventory);
						if($stmt->execute()){
							$stmt->close();
							$product_attribute_id = $this->connection->insert_id;
							if($uploadable != 'true'){
								//set to the same cover as the old one
								if($this->isGivenAttributeSetAsCover($product_id, $attribute_name_id)){
									$this->updateCoverByRowId($product_attribute_id, $product_id);
								}
							}else{
								if($isCover == 'true'){
									$this->updateCoverByRowId($product_attribute_id, $product_id);
								}	
							}
							return true;
						}
					}
				}
			}
			return false;
		}
		
		
		public function getAttributePhotoForProduct($product_id, $attribute_name, $full_path = true){
			$at_n = new Attribute_Name();
			$attribute_name_id = $at_n->getAttributeIdFromName($attribute_name);
			if($attribute_name_id !== false){
				if($this->isAttributeNameIdForProductExists($product_id, $attribute_name_id)){
					$shot_path = $this->getColumnBySelector('shot_path', 'attribute_name_id', $attribute_name_id);
					if($shot_path !== false){
						return ($full_path)?MEDDIR.$shot_path:$shot_path;
					}
				}
			}
			return false;
		}
		
		public function getNumberForGivenAttributeOfProduct($product_id, $attribute_name_id){
			$stmt = $this->connection->prepare("SELECT `product_attribute_id` FROM `$this->table_name` WHERE `product_id` = ?  AND `attribute_name_id` = ?");
			if($stmt){
				$stmt->bind_param('ii', $product_id, $attribute_name_id);
				if($stmt->execute()){
					 $result = $stmt->get_result();
					 if($result !== false){
					 	$stmt->close();
						return $result->num_rows;
					 }
				}
			}
			echo $this->connection->error;
			return false;
		}
		
		public function isAttributeForProductAvailableForCart($product_id, $attribute_name_id, $product_size_id){
			$stmt = $this->connection->prepare("SELECT `product_attribute_id`, `inventory` FROM `$this->table_name` WHERE `product_id` = ? AND `attribute_name_id` = ? AND `product_size_id` = ? AND `inventory` > 0 LIMIT 1");
			if($stmt){
				$stmt->bind_param('iii', $product_id, $attribute_name_id,$product_size_id );
				if($stmt->execute()){
					 $result = $stmt->get_result();
					 if($result->num_rows == 1){
					 	$stmt->close();
					 	$row = $result->fetch_assoc();
						$product_attribute_id = $row['product_attribute_id'];
						$item_in_cart = $this->countSameItemInCart($product_attribute_id) +1;
						return ($item_in_cart > $row['inventory'])?false:$product_attribute_id;
						
					 }
				}
			}
			echo $this->connection->error;
			return false;
		}
		
		public function countSameItemInCart($product_attribute_id){
			if(isset($_COOKIE['cart_items'])){
				foreach(explode(',',$_COOKIE['cart_items']) as $pair   ){
					$ary = explode(':',$pair);
					if($ary[0] == $product_attribute_id){
						return $ary[1];
					}
				}
			}
			return 0;
		}
		
		public function updateAttributeQuanPair($cart_items,$product_attribute_id, $quantity = false ){
			$attribute_id_quantity_pair = $product_attribute_id.':';
			$position_start = stripos(','.$cart_items, ','.$attribute_id_quantity_pair); //pos of first comma
			if($position_start !== false){
				$position_end = stripos($cart_items, ',' ,$position_start+1); //pos of second comma
				if($position_end !== false){
					$old_attribute_quantity_pair = trim(substr($cart_items, $position_start, $position_end - $position_start),',');
				}else{
					$old_attribute_quantity_pair = trim(substr($cart_items, $position_start),',');
				}
				$pair_array = explode(':', $old_attribute_quantity_pair);
				if($quantity !== false){
					$pair_array[1] = $quantity;
				}else{
					$pair_array[1]++;
				}
				$updated_attribute_quantity_pair = implode(':', $pair_array);
				$cart_items = str_replace($old_attribute_quantity_pair, $updated_attribute_quantity_pair, $cart_items);
			}else{
				$cart_items = $cart_items.','.$attribute_id_quantity_pair.'1';
			}
			return trim($cart_items, ',');
		}
		
		
		
		public function addToCart($product_id, $attribute_name_id, $size_text){
			$at_n = new Attribute_Name();
			$size = new Product_Size();
			$product_size_id = $size->getProductSizeIdBySizeText($size_text);
			if($product_size_id !== false){
				$product_attribute_id = $this->isAttributeForProductAvailableForCart($product_id, $attribute_name_id, $product_size_id);
				if($product_attribute_id !== false){
					$cart_items = '';
					if(isset($_COOKIE['cart_items'])){
						$cart_items = $_COOKIE['cart_items'];
					}
					$new_cart_items =  $this->updateAttributeQuanPair($cart_items,$product_attribute_id);
					setCookie('cart_items',$new_cart_items,time() + 60*60*24*30, '/');
					return true;
				}
			}
			return false;
		}
		
		public function getCartItem(){
			if(isset($_COOKIE['cart_items'])){
				return $cart_items = explode(',',$_COOKIE['cart_items']);
			}else{
				return false;
			}
		}
		
		
		public function loadPopupCart(){
			if(isset($_COOKIE['cart_items'])){
				$items = $this->getCartItem();
				if(sizeof($items) > 0){
					$content = '';
					foreach($items as $atr_pair){
						$pair_array = explode(':',$atr_pair);
						$content .= $this->renderPopupCartItemByProductAttributeId($pair_array[0], $pair_array[1]);
					}
					return $content;
				}
			}else{
				return 'The bag is empty';	
			}
			
		}
		
		public function renderPopupCartItemByProductAttributeId($product_attribute_id, $quantity){
			$cart_item = $this->getAllColumnsById($product_attribute_id);
			if($cart_item !== false){
				$at_n = new Attribute_Name();
				$size = new Product_Size();
				$product = new Product();
				$cart_item['price'] = $quantity*$product->getProductPriceByProductId($cart_item['product_id']);
				$cart_item['title'] = $product->getProductTitleByProductId($cart_item['product_id']);
				$cart_item['product_url'] = SHOP_DIR.$product->getProductRedirectUrlByProductId($cart_item['product_id']);
				$cart_item['attribute_name'] = $at_n->getAttributeNameFromId($cart_item['attribute_name_id']);
				$cart_item['size'] = $size->getSizeFromId($cart_item['product_size_id']);
				$cart_item['url'] = MEDDIR.$cart_item['shot_path'];
				$cart_item['quantity'] = $quantity;
				ob_start();
				include(TEMPLATE_PATH.'popup_cart_item_view.phtml');
				$content = ob_get_clean();
				return $content;
			}else{
				return '';
			}
		}
		
		
		public function getCartItemNum(){
			if(isset($_COOKIE['cart_items'])){
				$cart_items = trimExplode($_COOKIE['cart_items'], CART_DELIMITER);
				return sizeof($cart_items);
			}else{
				return 0;
			}
		}
		
		
		public function renderCheckoutCartItemByProductAttributeId($product_attribute_id, $quantity, &$subtotal){
			$cart_item = $this->getAllColumnsById($product_attribute_id);
			if($cart_item !== false){
				$at_n = new Attribute_Name();
				$size = new Product_Size();
				$product = new Product();
				$cart_item['price'] = $quantity*$product->getProductPriceByProductId($cart_item['product_id']);
				$cart_item['product_url'] = SHOP_DIR.$product->getProductRedirectUrlByProductId($cart_item['product_id']);
				$cart_item['title'] = $product->getProductTitleByProductId($cart_item['product_id']);
				$cart_item['attribute_name'] = $at_n->getAttributeNameFromId($cart_item['attribute_name_id']);
				$cart_item['size'] = $size->getSizeFromId($cart_item['product_size_id']);
				$cart_item['url'] = MEDDIR.$cart_item['shot_path'];
				$cart_item['quantity'] = $quantity;
				$subtotal+=$cart_item['price'];
				ob_start();
				include(TEMPLATE_PATH.'shopping_bag_checkout_item.phtml');
				$content = ob_get_clean();
				return $content;
			}else{
				return '';
			}
		}
		
		public function loadCheckoutBagView(){
			
			if(isset($_COOKIE['cart_items'])){
				$items = $this->getCartItem();
				if(sizeof($items) > 0){
					$checkout_body_content = '';
					$subtotal = 0;
					foreach($items as $atr_pair){
						$pair_array = explode(':',$atr_pair);
						$checkout_body_content .= $this->renderCheckoutCartItemByProductAttributeId($pair_array[0], $pair_array[1], $subtotal);
					}
					
					ob_start();
					include(TEMPLATE_PATH.'checkout_action.phtml');
					$checkout_footer_content = ob_get_clean();
					
					ob_start();
					include(TEMPLATE_PATH.'shopping_bag_checkout_view.phtml');
					$content = ob_get_clean();
					return $content;
				}
			}else{
				ob_start();
				include(TEMPLATE_PATH.'shopping_bag_empty_state_view.phtml');
				$content = ob_get_clean();
				return $content;
			}
		
			
		}
		
		
		public function getCheckoutProductInfoWithAttributes($product_attribute_id, $request_quantity){
			$product = $this->getAllColumnsById($product_attribute_id);
			if($request_quantity <= $product['inventory']){
				$at_n = new Attribute_Name();
				$size = new Product_Size();
				$pro = new Product();
				$product['price'] = $pro->getProductPriceByProductId($product['product_id']);
				$product['title'] = $pro->getProductTitleByProductId($product['product_id']);
				$product['attribute_name'] = $at_n->getAttributeNameFromId($product['attribute_name_id']);
				$product['size'] = $size->getSizeFromId($product['product_size_id']);
				$product['url'] = MEDDIR.$product['shot_path'];
				return $product;
			}else{
				return false;
			}
		}
		
		
		public function removeFromCart($product_attribute_id){
			if(isset($_COOKIE['cart_items'])){
				$cart_items_array = explode(',',$_COOKIE['cart_items']);
				$target_item = '';
				foreach($cart_items_array as $item){
					$pair = explode(':',$item);
					if($pair[0] == $product_attribute_id){
						$target_item = $item;
						break;
					}
				}
				$new_cart_items = implode(',',array_diff($cart_items_array, array($target_item)));
				setCookie('cart_items',$new_cart_items,time() + 60*60*24*30, '/');
				return true;
			}
			return false;
		}
		
		
		
		
		
		
		
	}


?>