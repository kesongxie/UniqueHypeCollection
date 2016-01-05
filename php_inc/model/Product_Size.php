<?php
	include_once 'Core_Table.php';
	class Product_Size extends Core_Table{
		private $table_name = 'product_size';
		
		public function __construct(){
			parent::__construct($this->table_name);
		}
		
		public function getSizeFromId($size_id){
			return $this->getColumnById('size',$size_id);
		}
		
		public function getProductSizeIdBySizeText($size_text){
			return $this->getColumnBySelector('product_size_id', 'size', $size_text);
		}
		
		public function getAvailableSize(){
			$stmt = $this->connection->prepare("SELECT `size` FROM `$this->table_name`");
			if($stmt->execute()){
				 $result = $stmt->get_result();
				 if($result !== false && $result->num_rows >= 1){
				 	$rows = $result->fetch_all(MYSQLI_ASSOC);
				 	$stmt->close();
					return $rows;
				 }
			}
			return false;
		}
		
		
		
		
		
				
	
	}


?>