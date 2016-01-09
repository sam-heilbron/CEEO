<?php
	
	trait Stomper_trait {
		
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
		protected function example() {
			echo $this->uid;
		}
		
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
		
		protected function getAllMaterials() {
			$query = "SELECT name, q_avail, q_reserved, q_removed, max_checkout_q, low_q_thresh FROM Material";
			return $this->EndpointResponse($query, true);
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
				return $this->EndpointResponse($queryArray, false);
			} catch (Exception $e) {
				throw $e;
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
				WHERE tr.tid = ".$this->tid." and tr.res_type = '".$transaction_type."' ";
			return $this->EndpointResponse($query, true);
		}
		
		/* if valid return mid */
		private function _validateMaterialCheckOut($m, $q) {
			$query = "SELECT (mid) FROM Material WHERE name = '".$m."'";
			return $this->EndpointResponse($query, true)[0]['mid'];
		}
		private function _getNewTransactionID() {
			$query = "SELECT MAX(trid) AS trid FROM Transaction";
			return $this->EndpointResponse($query, true)[0]['trid'] + 1;
		}
		

	} ## Stomper_trait
?>