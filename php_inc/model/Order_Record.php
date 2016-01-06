<?php
	include_once 'Core_Table.php';
	class Order_Record extends Core_Table{
		private $table_name = 'order_record';
		
		public function __construct(){
			parent::__construct($this->table_name);
		}
		
		public function insertRecord($firstname, $lastname, $email, $phone, $shipping_address, $order_time){
			$status = 'not ship yet';
			$stmt = $this->connection->prepare("INSERT INTO `$this->table_name` (`firstname`, `lastname`,`email`,`phone`, `shipping_address`, `order_time`, `status`) VALUES (?, ?, ?, ?, ?, ?, ?) ");
			if($stmt){
				$stmt->bind_param('sssssss',$firstname, $lastname, $email, $phone, $shipping_address, $order_time, $status);
				if($stmt->execute()){
					$stmt->close();
					return $this->connection->insert_id;
				}
			}
			echo $this->connection->error;
			return false;
		}
		
		
		
		public function loadOrderRecord(){
			$order_records = $this->getAllRows();
			if($order_records !== false){
				$record_content = '';
				$order_item_record = new Order_Item_Record();
				foreach($order_records as $record){
					$record['item_description'] = $order_item_record->loadOrderItemRecordByOrderId($record['order_record_id']);
					$record['total'] = $order_item_record->getOrderTotalByOrderId($record['order_record_id']);
					ob_start();
					include(TEMPLATE_PATH.'admin_order_record_row.phtml');
					$record_content .= ob_get_clean();
				}
				return $record_content;
			}
			return false;
		}
	
		public function updateOrderStatus($order_record_id, $order_status){
			var_dump('he');
			var_dump($this->setColumnById('status', $order_status, $order_record_id));	
		}
				
	
	}


?>