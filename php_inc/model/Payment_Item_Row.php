<?php
	include_once 'Core_Table.php';
	class Payment_Item_Row extends Core_Table{
		private $table_name = 'payment_item_row';
		
		public function __construct(){
			parent::__construct($this->table_name);
		}
		
		
		public function insertPaymentItemRow($payment_id, $product_attribute_id, $order_quantity, $total){
			$stmt = $this->connection->prepare("INSERT INTO `$this->table_name` (`payment_id`, `product_attribute_id`,`order_quantity`,`total`) VALUES (?, ?, ?, ?) ");
			if($stmt){
				$stmt->bind_param('siid',$payment_id, $product_attribute_id, $order_quantity, $total);
				if($stmt->execute()){
					$stmt->close();
					return $this->connection->insert_id;
				}
			}
			echo $this->connection->error;
			return false;
		}
		
		
		
		public function getItemDescriptionByPaymentId($payment_id){
			$item_records = $this->getAllRowsMultipleColumnsBySelector(array('product_attribute_id', 'order_quantity','total'), 'payment_id', $payment_id, $this->table_name.'_id');
			if($item_records !== false){
				$records = '';
				$atr = new Product_Attribute();
				foreach($item_records as $record){
					$description =  $atr->getProductTitleByProductAttributeId($record['product_attribute_id']);
					$description  .= ' / '.$atr->getProductAttributeNameByProductAttributeId($record['product_attribute_id']);
					$description  .= ' / '.$atr->getProductSizeByProductAttributeId($record['product_attribute_id']);
					$description  .= ' / '.$record['order_quantity'];
					$description  .= ' / '.getCurrencyFormat($record['total']);
					$record['item_description'] = $description;
					$record['item_url'] = $atr->getProductUrlByProductAttributeId($record['product_attribute_id']);
					ob_start();
					include(TEMPLATE_PATH.'item_record_description_row.phtml');
					$row = ob_get_clean();
					$records .= $row;
				}
				return $records;
			}
			return false;
		
		
		}
			
		public function getItemsOrderTotalByPaymentId($payment_id){
			$stmt = $this->connection->prepare("SELECT SUM(total) AS total FROM `$this->table_name` WHERE `payment_id` = ?");
			if($stmt){
				$stmt->bind_param('s', $payment_id);
				if($stmt->execute()){
					 $result = $stmt->get_result();
					 if($result->num_rows == 1){
					 	$stmt->close();
					 	$row = $result->fetch_assoc();
						return $row['total'];
					 }
				}
			}
			return 0;
			
		}	
			
		
		public function getConfirmatioItemRowsByPaymentId($payment_id){
			$item_records = $this->getAllRowsMultipleColumnsBySelector(array('product_attribute_id', 'order_quantity','total'), 'payment_id', $payment_id, $this->table_name.'_id');
			if($item_records !== false){
				$rows = '';
				$atr = new Product_Attribute();
				foreach($item_records as $record){
					$record['title'] = $atr->getProductTitleByProductAttributeId($record['product_attribute_id']);
					$record['attribute_name'] = $atr->getProductAttributeNameByProductAttributeId($record['product_attribute_id']);
					$record['size'] = $atr->getProductSizeByProductAttributeId($record['product_attribute_id']);
					$record['quantity']  = $record['order_quantity'];
					$record['total']  = getCurrencyFormat($record['total']);
					$record['url'] = $atr->getProductUrlByProductAttributeId($record['product_attribute_id']);
					$record['shot_path'] = MEDDIR.$atr->getShotPathByProductAttributeId($record['product_attribute_id']);
					ob_start();
					include(TEMPLATE_PATH.'confirmation_item_row.phtml');
					$row = ob_get_clean();
					$rows .= $row;
				}
				return $rows;
			}
			return false;
		
		}
		
				
	
	}


?>