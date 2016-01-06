<?php
	include_once 'Core_Table.php';
	class Product extends Core_Table{
		private $table_name = 'product';
		
		public function __construct(){
			parent::__construct($this->table_name);
		}
		
		public function getProductTitleByProductId($id){
			return $this->getColumnById('title',$id);
		}
		
		public function getProductPriceByProductId($id){
			return $this->getColumnById('price',$id);
		}
		
		public function getProductRedirectUrlByProductId($id){
			return $this->getColumnById('url',$id);
		}
		
		public function productHomeRenderer(){
			$product_collection = $this->getAllRowsMultipleColumns(array('product_id','title', 'price', 'url'));
			$products = '';
			$atr = new Product_Attribute();
			foreach($product_collection as $product){
				if($atr->isAttributeExistsForProduct($product['product_id'])){
					$product['cover_path_url'] = $atr->getProductCoverPathByProductId($product['product_id']);
					ob_start();
					include(TEMPLATE_PATH.'product_item.phtml');
					$products.= ob_get_clean();
				}
			}
			return $products;
		}
		
		
		public function adminProductSellingRenderer(){
			$product_collection = $this->getAllRowsMultipleColumns(array('product_id','title', 'price', 'url'));
			$products = '';
			$atr = new Product_Attribute();
			if($product_collection !== false){
				foreach($product_collection as $product){
					$product['cover_path_url'] = $atr->getProductCoverPathByProductId($product['product_id']);
					ob_start();
					include(TEMPLATE_PATH.'admin_product_item.phtml');
					$products.= ob_get_clean();
				}
				return $products;
			}else{
				ob_start();
				include(TEMPLATE_PATH.'no_product_empty_state.phtml');
				$content= ob_get_clean();	
				return $content;
			}
			
		}
		
		
		
		public function loadProductInfoByUrl($url){
			$product = $this->getMultipleColumnsBySelector(array('product_id','title','description', 'price'), 'url', $url);
			if($product !== false){
				$atr = new Product_Attribute();
				$atr_name = new Attribute_Name();
				$product['cover_path_url'] = $atr->getProductCoverPathByProductId($product['product_id']);
				$product['products_snap_shots'] = $atr->getProductSnapShotCollectionByProductId($product['product_id']);
				$product['products_size_available'] = $atr->getProductAvailableSizeCollectionByProductId($product['product_id']);
				$product_size = new Product_Size();
				ob_start();
				include(TEMPLATE_PATH.'product_preview.phtml');
				$product_preview = ob_get_clean();
				return $product_preview;
			}
			return false;	
		}
		
		
		public function loadEditProductInfoByUrl($url){
			$product = $this->getMultipleColumnsBySelector(array('product_id','title','description', 'price'), 'url', $url);
			if($product !== false){
				$atr = new Product_Attribute();
				$atr_name = new Attribute_Name();
				$product['cover_path_url'] = $atr->getProductCoverPathByProductId($product['product_id']);
				$product['attribute_rows'] = $atr->getProductAttributeCollectionByProductId($product['product_id']);
				$product_size = new Product_Size();
				ob_start();
				include(TEMPLATE_PATH.'admin_product_preview.phtml');
				$product_preview = ob_get_clean();
				return $product_preview;
			}
			return false;	
		}
		
		
		public function getProductTitleByUrl($url){
			$title = $this->getColumnBySelector('title', 'url', $url);
			return $title;
		}
		
		public function saveProductGeneralInfo($product_id, $title, $desc, $price){
			$this->setColumnByNumericSelector('title', $title, 'product_id', $product_id );
			$title_array = preg_split('/ /', $title, -1, PREG_SPLIT_NO_EMPTY);
			$url = strtolower(implode('-',$title_array));
			$this->setColumnByNumericSelector('url', $url, 'product_id', $product_id );
			$this->setColumnByNumericSelector('description', $desc, 'product_id', $product_id );
			if(is_numeric($price) && $price > 0){
				$this->setColumnByNumericSelector('price', $price, 'product_id', $product_id );
			}
		}
		
		public function isUrlExistForProduct($url){
			return $this->isStringValueExistingForColumn($url, 'url');
		}
		
		
		
		public function addNewProduct($title, $description, $price){
			if(!is_numeric($price) ||  $price <= 0){
				return false;	
			}
			$title_array = preg_split('/ /', $title, -1, PREG_SPLIT_NO_EMPTY);
			$url = strtolower(implode('-',$title_array));
			if(!$this->isUrlExistForProduct($url)){
				$stmt = $this->connection->prepare("INSERT INTO `$this->table_name` (`title`, `description`,`price`,`url`) VALUES (?,?,?,?) ");
				if($stmt){
					$stmt->bind_param('ssds', $title, $description, $price, $url);
					if($stmt->execute()){
						$stmt->close();
						return $this->connection->insert_id;
					}
				}
			}
			echo $this->connection->error;
			return false;
		}

				
	
	}


?>