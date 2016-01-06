<?php
	include_once 'Core_Table.php';
	class Admin_Credential extends Core_Table{
		private $table_name = 'admin_credential';
		
		public function __construct(){
			parent::__construct($this->table_name);
		}
		
		public function loadAdminLoginBody(){
			$stmt = $this->connection->prepare("SELECT * FROM `$this->table_name`");
			if($stmt){
				if($stmt->execute()){
					$result = $stmt->get_result();
					if($result->num_rows > 0){
						$body_path = TEMPLATE_PATH.'admin_login_box.phtml';	
					}else{
						$body_path = TEMPLATE_PATH.'admin_register.phtml';	
					}
				}
			}
			ob_start();
			include($body_path);
			$admin_body = ob_get_clean();
	
			ob_start();
			include TEMPLATE_PATH.'admin_login.phtml';	
			$content = ob_get_clean();
			return $content;
		}
		
		public function createNewAdmin($username, $password){
			$pass_hash = password_hash($password, PASSWORD_DEFAULT);
			$stmt = $this->connection->prepare("INSERT INTO `$this->table_name` (`username`,`password`) VALUES (?,?) ");
			if($stmt){
				$stmt->bind_param('ss', $username, $pass_hash);
				if($stmt->execute()){
					$stmt->close();
					return true;
				}
			}
			echo $this->connection->error;
			return false;
		}
		
		public function login($username, $password){
			$result = $this->getMultipleColumnsBySelector(array('admin_credential_id','password'), 'username', $username);
			if($result !== false){
				if(password_verify($password, $result['password'])){
					$_SESSION['admin_id'] = $result['admin_credential_id'];
				}
				return true;
			}
			return false;
		}
		
		
		
		
	}
?>