<?php
	include_once 'Core_Table.php';
	class Order_Item_Record extends Core_Table{
		private $table_name = 'order_item_record';
		
		public function __construct(){
			parent::__construct($this->table_name);
		}
		
		
		
		public function insertItemRecord($order_record_id, $item_description, $item_url, $total){
			$stmt = $this->connection->prepare("INSERT INTO `$this->table_name` (`order_record_id`, `item_description`,`item_url`, `total`) VALUES (?,?,?,?) ");
			if($stmt){
				$stmt->bind_param('issd',$order_record_id, $item_description, $item_url, $total);
				if($stmt->execute()){
					$stmt->close();
					return $this->connection->insert_id;
				}
			}
			echo $this->connection->error;
			return false;
		
		}
		
		public function loadOrderItemRecordByOrderId($order_record_id){
			$item_records = $this->getAllRowsMultipleColumnsBySelector(array('item_description', 'item_url','total'), 'order_record_id', $order_record_id, $this->table_name.'_id');
			if($item_records !== false){
				$records = '';
				foreach($item_records as $record){
					$record['item_url'] = $record['item_url'];
					ob_start();
					include(TEMPLATE_PATH.'item_record_description_row.phtml');
					$row = ob_get_clean();
					$records .= $row;
				}
				return $records;
			}
			return false;
		}
		
		
		public function getOrderTotalByOrderId($order_record_id){
			$stmt = $this->connection->prepare("SELECT SUM(total) AS total FROM `$this->table_name` WHERE `order_record_id` = ?");
			if($stmt){
				$stmt->bind_param('i', $order_record_id);
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
		
	
		
				
	
	}


?>