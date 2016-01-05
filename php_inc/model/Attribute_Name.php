<?php
	include_once 'Core_Table.php';
	class Attribute_Name extends Core_Table{
		private $table_name = 'attribute_name';
		
		public function __construct(){
			parent::__construct($this->table_name);
		}
		
		public function getAttributeNameFromId($attribute_id){
			return $this->getColumnById('attribute_name',$attribute_id);
		}
		
		public function getAttributeIdFromName($attribute_name){
			return $this->getColumnBySelector('attribute_name_id', 'attribute_name',$attribute_name);
		}
		
		
		public function addNewAttribute($attribute_name){
			$attribute_name_id = $this->getAttributeIdFromName($attribute_name);
			if($attribute_name_id === false){
				$attribute_name = strtoupper($attribute_name);
				$stmt = $this->connection->prepare("INSERT INTO `$this->table_name` (`attribute_name`) VALUES (?) ");
				if($stmt){
					$stmt->bind_param('s', $attribute_name);
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