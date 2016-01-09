<?php

/*  create class for db_events. All apis should call this. should be protected by api */

	require_once 'StompAPI_rules.php';
	
	class Stomper extends StompAPI_rules {
		private $result = null;
		private $uid = null;
		private $tid = null;

		public function __construct($request, $origin, $uid, $tid) {
			$this->uid = $uid;
			$this->tid = $tid;
			
			/* Construct StompAPI_rules */
			parent::__construct($request);
		}
		
		/**************************************************************************************************************/
		/*******************************************  PUBLIC API CALLS  ***********************************************/
		/**************************************************************************************************************/
		
		/*
			Valid Enpoints:
		
			/Stomp/checkout/reserve
			/Stomp/checkout/remove
			/Stomp/me/reservation
			/Stomp/me/checkedOut
			/Stomp/getAllMaterials
			/Stomp/getAllUsers
			
		*/
		
		protected function example() {}
		
		protected function checkout() {
			$d = (array_key_exists (0, $this->descriptor)) ? $this->descriptor[0] : null;
			switch($d) {
				case 'reserve': 
					$result = $this->_submitCheckOut("reserve", "q_reserved");
					break;
				case 'remove': 
					$result = $this->_submitCheckOut("remove", "q_removed");
					break;
				default: 
					throw new Exception("Invalid Checkout Type");
			}
			return $result;
		}
		
				
		protected function me() {
			$d = (array_key_exists (0, $this->args)) ? $this->args[0] : null;
			switch($d) {
				case 'reservation': 
					$result = $this->_getMyTransactions("reserve");
					break;
				case 'checkedOut': 
					$result = $this->_getMyTransactions("remove");
					break;
				default: 
					throw new Exception("Invalid Personal Information Type");
			}
			return $result;
		}
		
		protected function getAllUsers() {
			$query = "SELECT username, f_name, l_name, phone, email FROM Stomper";
			return $this->implementQueryStream($query);
		}
		
		protected function getAllMaterials() {
			$query = "SELECT name, q_avail, q_reserved, q_removed, max_checkout_q, low_q_thresh FROM Material";
			return $this->implementQueryStream($query);
		}
		
		//v2
		protected function getMySavedSearches() {}

		
		/**************************************************************************************************************/
		/******************************************  PRIVATE HELPER METHODS  ******************************************/
		/**************************************************************************************************************/
		
		//include in v2 --> private function saveCheckOut() {}
		private function _submitCheckOut($res_type, $q_type) {
			try {
				//still need to use tid and uid
				$trid = $this->_getNewTransactionID();
				$transaction = "INSERT INTO Transaction 
									(trid, tid, uid, mid, quantity, transaction_date, res_type, action_date) VALUES";
				foreach ($this->args as $material => $quantity) {
					$mid = $this->_validateMaterialCheckOut($material, $quantity);
					$transaction .= "(".$trid.", 1, 2, ".$mid.", ".$quantity.", NOW(), '".$res_type."', ADDTIME(NOW(), '14 0:00:00.00')),";
					$queryArray[] = "UPDATE Material SET 
										".$q_type." = ".$q_type." + ".$quantity.",
										q_avail = q_avail - ".$quantity."
									 WHERE mid = '".$mid."' ";
				}
				$queryArray[] = rtrim($transaction, ","); //remove last comma
				$this->implementQueryStream($queryArray);
				return $this->_genericSuccess();
			} catch (Exception $e) {
				return array("status" => "Error", "message" => $e->getMessage());
			}
		}
		
		
		private function _getMyTransactions($transaction_type) {
			$query = "
				SELECT m.name, tr.quantity, tr.transaction_date, tr.action_date, s.f_name, s.l_name 
				FROM Transaction AS tr 
				INNER JOIN Material AS m 
				USING (mid)
				INNER JOIN Stomper AS s 
				USING (uid)
				WHERE tr.tid = 1 and tr.res_type = '".$transaction_type."' ";
			return $this->implementQueryStream($query);
		}
		
		/* if valid return mid */
		private function _validateMaterialCheckOut($m, $q) {
			$query = "SELECT (mid) FROM Material WHERE name = '".$m."'";
			return $this->implementQueryStream($query)[0]['mid'];
		}
		private function _getNewTransactionID() {
			$query = "SELECT MAX(trid) AS trid FROM Transaction";
			return $this->implementQueryStream($query)[0]['trid'] + 1;
		}
	} ## StompAPI
?>