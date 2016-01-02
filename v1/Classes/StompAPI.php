<?php

	require_once 'Conn.php';
	require_once 'StompAPI_rules.php';
	
	class StompAPI extends StompAPI_rules {
		private $dbhost = null;
		private $dbuser = null;
		private $dbpass = null;
		private $dbname = null;
		private $conn = null;
		private $result = null;
		private $returnArray = array();
		

		public function __construct($request, $origin) {
			/* Construct StompAPI_rules */
			parent::__construct($request);
		}
		
		/* openConnection */
		private function openConnection() {
			$this->dbhost = Conn::$dbhost;
			$this->dbuser = Conn::$dbuser;
			$this->dbpass = Conn::$dbpass;
			$this->dbname = Conn::$dbname;
			$this->conn = new mysqli($this->dbhost, $this->dbuser, $this->dbpass, $this->dbname);
			if (mysqli_connect_errno()) echo new Exception("Could not establish connection with database");
		}

		/* getConnection */
		private function getConnection() {
			return $this->conn;
		}

		/* closeConnection */
		private function closeConnection() {
			if ($this->conn != null) $this->conn->close();
		}
		
		private function ApplyQuery($q) {
			$this->openConnection();
			return $this->conn->query($q);
		}
		

		public function example($p) {
			echo "Parameter passed to example is " . $p[0];
        	if ($this->req_method == 'GET') {
         	   return $returnArray["r"] = "Example method";
        	} else {
            	return $returnArray["r"] = "Only accepts GET requests";
        	}
     	}

		/**************************************************************************************************************/
		/*******************************************  PUBLIC API CALLS  ***********************************************/
		/**************************************************************************************************************/
		
		
		/* getAllUsers */
		public function getAllUsers() {
			$result = $this->ApplyQuery("SELECT * FROM Stomper");
			while ($row = $result->fetch_assoc()) $returnArray[] = $row;
			return $returnArray;
		}

		/* getUserDetailsWithPassword */
		public function getUserDetailsWithPassword($email, $userPassword) {
			$result = $this->ApplyQuery("select id,user_email from users where user_email='" . $email . "' and user_password='" .$userPassword . "'");

			if ($result != null && (mysqli_num_rows($result) == 1)) return $result->fetch_assoc(); 
			else return array("status" => "Error", "message" => "Sorry, that user does not exist");
		}

		
		/* main api calls */
		public function getAllMaterials() {
			$result = $this->ApplyQuery("SELECT * FROM Material");
			while ($row = $result->fetch_assoc()) $returnArray[] = $row;
			return $returnArray;	
		
		}

		public function submitCheckOut() {}
		
		public function getMyReservations() {}
		
		public function getMyCurrentCheckOuts() {}
		
		public function getMySavedSearches() {}
		
		public function saveCheckOut() {}
		
		
		
		/*
		*	Private Methods
		*
		*/

		private function getTeamByUserID($id) {
			$sql = "select * from teams where uid='" . $id . "'";
			$result = $this->conn->query($sql);
		}
	} ## StompAPI
?>