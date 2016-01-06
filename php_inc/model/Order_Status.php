<?php
	include_once 'Core_Table.php';
	class Order_Status extends Core_Table{
		private $table_name = 'order_status';
		
		public function __construct(){
			parent::__construct($this->table_name);
		}
		
		public function getOrderStatusById($order_status_id){
			return $this->getColumnById('status',$order_status_id);
		}
		
		public function getOrderStatusIdByStatus($status){
			return $this->getColumnBySelector('order_status_id', 'status', $status);
		}
		
		public function getDefaultOrderStatusId(){
			return $this->getOrderStatusIdByStatus('not ship yet');
		}
		
	}
?>