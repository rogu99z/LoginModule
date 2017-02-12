<?php

	class User{

		private $connection;
		private $session = "UserSession";
		public $logged_in = false;
		public $userdata;

		public function __construct(){
			$sessionId = session_id();

			if( strlen($sessionId) == 0){
				throw new Exception("No session existence.");
			}

			$this->connection = new mysqli($GLOBALS["hostname"], $GLOBALS["username"], $GLOBALS["password"], $GLOBALS["database"]);

			if( $this->connection->connect_error )
				throw new Exception("MySQL connection error ".$this->connection->connect_error);

			$this->_isValidUser();
			$this->_updateMostRecentActivity();
		}

		public function create($username, $password){
			$sql = "INSERT INTO users (username, password, mostRecentActivity, creationTime) VALUES ('$username', '$password', NOW(), NOW())";
			return $this->connection->query($sql);
		}

		public function login($username, $password){
			$sql = "SELECT id FROM users WHERE username='$username' AND password='$password' LIMIT 1";
			$result = $this->connection->query($sql);

			if($result->num_rows > 0){
				$id = $result->fetch_row()[0];
				$_SESSION[$this->session]["id"] = $id;
				$this->logged_in = true;
				return $id;
			}

			return false;
		}

		public function logout(){

			if(isset($_SESSION[$this->session])){
				unset($_SESSION[$this->session]);
			}

			$this->logged_in = false;
		}

		public function setPassword($password, $id){
			$sql = "UPDATE users SET password='$password' WHERE id='$id' LIMIT 1";
			return $this->connection->query($sql);
		}

		public function getUsers(){
			$sql = "SELECT DISTINCT id, username, mostRecentActivity, creationTime FROM users ORDER BY username ASC";
			$result = $this->connection->query($sql);
			
			if( $result->num_rows == 0)
				return array();

			$users = array();
			$i = 0;

			while($row = $result->fetch_row()){		
				$users[$i]["id"] = $row[0];
				$users[$i]["username"] = $row[1];
				$users[$i]["mostRecentActivity"] = $row[2];
				$users[$i]["creationTime"] = $row[3];
				$i++;
			}

			return $users;
		}

		public function getSingleUser($id){

			if($id == null){
				$id = $_SESSION[$this->session]["id"];
			}

			$sql = "SELECT id, username, mostRecentActivity, creationTime FROM users WHERE id='$id' LIMIT 1";
			$result = $this->connection->query($sql);
			$row = $result->fetch_row();
			$user["id"] = $row[0];
			$user["username"] = $row[1];
			$user["mostRecentActivity"] = $row[2];
			$user["creationTime"] = $row[3];
			return $user;
		}

		public function delete($id){
			$sql = "DELETE FROM users WHERE id='$id'";
			$this->connection->query($sql);
			return;
		}

		private function _updateMostRecentActivity(){

			if(!$this->logged_in){
				return;
			}

			$id = $_SESSION[$this->session]["id"];
			$sql = "UPDATE users SET mostRecentActivity=NOW() WHERE id='$id' LIMIT 1";
			$this->connection->query($sql);
			$affected = $this->connection->affected_rows;

			if ($affected>1){
				throw new Exception("Exception updating last login ".$this->connection->error);
			}

			return;
		}

		private function _isValidUser(){

			if(!isset($_SESSION[$this->session]["id"])){
				return;
			}

			$id = $_SESSION[$this->session]["id"];
			$sql = "SELECT id FROM users WHERE id='$id' LIMIT 1";
			$result = $this->connection->query($sql);

			if($result->num_rows != 1){
				$this->logout();
				return;
			}

			$this->logged_in = true;
		}		
	}
	
?>