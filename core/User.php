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

			$host = $GLOBALS["hostname"];
			$database = $GLOBALS["database"];
			$user = $GLOBALS["username"];
			$password = $GLOBALS["password"];

			try {
				$this->connection = new PDO("mysql:host=$host;dbname=$database", $user, $password, array(PDO::MYSQL_ATTR_FOUND_ROWS => true));
				$this->_isValidUser();
				$this->_updateMostRecentActivity();
			}
			catch (PDOException $e) {
			    throw new Exception("MySQL connection error " . $e->getMessage());
			}
		}
 	
		public function create($username, $password){
			try {
				$preparedStatement = $this->connection->prepare("INSERT INTO users (username, password, mostRecentActivity, creationTime) VALUES (:username, :password, NOW(), NOW())");
				$preparedStatement->bindParam(":username", $username, PDO::PARAM_STR);
				$preparedStatement->bindParam(":password", $password, PDO::PARAM_STR);
				$preparedStatement->execute();
				return $preparedStatement->rowCount();

			}catch (PDOException $e) {
			    throw new Exception("MySQL connection error " . $e->getMessage());
			}
		}

		public function login($username, $password){
			try {

				$preparedStatement = $this->connection->prepare("SELECT id FROM users WHERE username=:username AND password=:password LIMIT 1");
				$preparedStatement->bindParam(":username", $username, PDO::PARAM_STR);
				$preparedStatement->bindParam(":password", $password, PDO::PARAM_STR);
				$preparedStatement->execute();
				$result = $preparedStatement->fetchAll();
				if(sizeof($result) == 1){
					$id = $result[0]["id"];
					$_SESSION[$this->session]["id"] = $id;
					$this->logged_in = true;
					return $id;
				}

			}catch (PDOException $e) {
			    throw new Exception("MySQL connection error " . $e->getMessage());
			}
		}

		public function logout(){

			if(isset($_SESSION[$this->session])){
				unset($_SESSION[$this->session]);
			}

			$this->logged_in = false;
		}

		public function setPassword($password, $id){
			$preparedStatement = $this->connection->prepare("UPDATE users SET password=:password WHERE id=:id LIMIT 1");
			$preparedStatement->bindParam(":password", $password, PDO::PARAM_INT);
			$preparedStatement->bindParam(":id", $id, PDO::PARAM_INT);
			$preparedStatement->execute();
			return $preparedStatement->fetch(PDO::FETCH_ASSOC);
		}

		public function getUsers(){
			$sql = "SELECT DISTINCT id, username, mostRecentActivity, creationTime FROM users ORDER BY username ASC";
			$result = $this->connection->query($sql);
			
			if( $result->rowCount() == 0)
				return array();

			$users = array();
			$i = 0;

			while($row = $result->fetch(PDO::FETCH_ASSOC)){
				$users[$i]["id"] = $row["id"];
				$users[$i]["username"] = $row["username"];
				$users[$i]["mostRecentActivity"] = $row["mostRecentActivity"];
				$users[$i]["creationTime"] = $row["creationTime"];
				$i++;
			}

			return $users;
		}

		public function getSingleUser($id){

			if($id == null){
				$id = $_SESSION[$this->session]["id"];
			}

			$preparedStatement = $this->connection->prepare("SELECT id, username, mostRecentActivity, creationTime FROM users WHERE id=:id LIMIT 1");
			$preparedStatement->bindParam(":id", $id, PDO::PARAM_INT);
			$preparedStatement->execute();
			$result = $preparedStatement->fetch(PDO::FETCH_ASSOC);
			$user["id"] = $result["id"];
			$user["username"] = $result["username"];
			$user["mostRecentActivity"] = $result["mostRecentActivity"];
			$user["creationTime"] = $result["creationTime"];
			return $user;
		}

		public function delete($id){
			$preparedStatement = $this->connection->prepare("DELETE FROM users WHERE id=:id");
			$preparedStatement->bindParam(":id", $id, PDO::PARAM_INT);
			$preparedStatement->execute();
			return;
		}

		private function _updateMostRecentActivity(){

			if(!$this->logged_in){
				return;
			}

			$id = $_SESSION[$this->session]["id"];
			$preparedStatement = $this->connection->prepare("UPDATE users SET mostRecentActivity=NOW() WHERE id=:id LIMIT 1");
			$preparedStatement->bindParam(":id", $id, PDO::PARAM_INT);
			$preparedStatement->execute();
			$result = $preparedStatement->fetch(PDO::FETCH_ASSOC);
			$affected = $result->rowCount();

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

			try{
				$preparedStatement = $this->connection->prepare("SELECT id FROM users WHERE id=:id LIMIT 1");
				$preparedStatement->bindParam(":id", $id, PDO::PARAM_INT);
				$preparedStatement->execute();
				$result = $preparedStatement->fetch(PDO::FETCH_ASSOC);

				if($result->rowCount() != 1){
					$this->logout();
					return;
				}

				$this->logged_in = true;
			}catch (PDOException $e) {
			    throw new Exception("MySQL connection error " . $e->getMessage());
			}
		}		
	}
	
?>