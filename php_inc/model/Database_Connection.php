<?php
	class Database_Connection{
		const HOSTNAME = "localhost";
		const USERNAME  = "root";
		const PASSWORD = "root";
		const DATABASE_NAME = "unique_hype_collection";
		private $connection = null;
		
		public function __construct(){
			$mysqli = new mysqli(self::HOSTNAME,self::USERNAME,self::PASSWORD,self::DATABASE_NAME);
			if(!$mysqli->connect_errno){
				 $this->connection = $mysqli; 
			}
		}
		
		public function getConnection(){
			return $this->connection;
		}
	}	
?>