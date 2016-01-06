<?php
	include_once 'Core_Table.php';
	class Order_Record extends Core_Table{
		private $table_name = 'order_record';
		
		public function __construct(){
			parent::__construct($this->table_name);
		}
		
		public function insertRecord($payment_id, $firstname, $lastname, $email, $phone, $shipping_address, $order_time){
			$order_status = new Order_Status();
			$order_status_default_id = $order_status->getDefaultOrderStatusId();
			$stmt = $this->connection->prepare("INSERT INTO `$this->table_name` (`payment_id`, `firstname`, `lastname`,`email`,`phone`, `shipping_address`, `order_time`, `order_status_id`) VALUES (?, ?, ?, ?, ?, ?, ?, ?) ");
			if($stmt){
				$stmt->bind_param('ssssssss',$payment_id, $firstname, $lastname, $email, $phone, $shipping_address, $order_time, $order_status_default_id);
				if($stmt->execute()){
					$stmt->close();
					return $this->connection->insert_id;
				}
			}
			echo $this->connection->error;
			return false;
		}
		
		public function loadOrderRecord(){
			$order_records = $this->getAllRows('order_status_id',true);
			if($order_records !== false){
				$record_content = '';
				$payment_item_row = new Payment_Item_Row();
				$status = new Order_Status();
				foreach($order_records as $record){
					$record['item_description'] = $payment_item_row->getItemDescriptionByPaymentId($record['payment_id']);
					$record['total'] = $payment_item_row->getItemsOrderTotalByPaymentId($record['payment_id']);
					$record['status'] = $status->getOrderStatusById($record['order_status_id']);
					ob_start();
					include(TEMPLATE_PATH.'admin_order_record_row.phtml');
					$record_content .= ob_get_clean();
				}
				return $record_content;
			}
			return false;
		}
	
		public function updateOrderStatus($order_record_id, $order_status){
			$status = new Order_Status();
			$order_status_id = $status->getOrderStatusIdByStatus($order_status);
			if($order_status_id !== false){
				$this->setColumnById('order_status_id', $order_status_id, $order_record_id);			
			}
		}
				
				
		public function printOrderConfirmationPgae($payment_id){
			$shipping_address = $this->getColumnBySelector('shipping_address', 'payment_id', $payment_id);
			$payment_item_row = new Payment_Item_Row();
			$order_detail = $payment_item_row->getConfirmatioItemRowsByPaymentId($payment_id);
			$total = $payment_item_row->getItemsOrderTotalByPaymentId($payment_id);
			ob_start();
			include(TEMPLATE_PATH.'order_confirm.phtml');
			$confirm = ob_get_clean();
			return $confirm;
		}
	
	}


?>