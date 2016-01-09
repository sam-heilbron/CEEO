<?php

/*  create class for db_events. All apis should call this. should be protected by api */

	require_once 'Conn.php';
	abstract class DB_Event extends Conn {
		private $conn = null;
		
		public function __construct() {
			/* Construct Conn */
			parent::__construct();
		}
		
		/* openConnection */
		private function _openConnection() {
			$this->conn = new mysqli($this->dbhost, $this->dbuser, $this->dbpass, $this->dbname);
			if (mysqli_connect_errno()) throw new Exception("Could not establish connection with database");
		}
		
		private function _getConnection() {
			return $this->conn;
		}
		
		private function _closeConnection() {
			if ($this->conn != null) $this->conn->close();
		}
		
		private function _applyQuery($q) {
			$r = $this->conn->query($q);
			if(!$r) throw new Exception("Query Error. Poorly formatted data");
			return $r;
		}
		
		private function _BeginTransaction() {
			$this->_openConnection();
			$this->conn->begin_transaction();
		}
		
		private function _EndTransaction() {
			$this->conn->commit();
			$this->_closeConnection();
		}
		
		protected function implementQueryStream($queryArray) {
			if (!is_array($queryArray)) $queryArray = array($queryArray);
			try {
				$returnArray = Array();
				$this->_BeginTransaction();
				foreach($queryArray as $sql) {
					$result = $this->_applyQuery($sql);
					if(!is_object($result))  $returnArray[] = $result;
					else while ($row = $result->fetch_assoc()) $returnArray[] = $row;
				}
				$this->_EndTransaction();
				return $returnArray;
			} catch (Exception $e) {
				//some sort of rollback to undo previous queries completed in this transaction
				throw $e;
			}
		}
		
	} ## DB_Event
?>