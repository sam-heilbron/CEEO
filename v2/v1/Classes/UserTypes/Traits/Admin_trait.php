<?php
	
	trait Admin_trait {
		
		/**************************************************************************************************************/
		/*******************************************  PUBLIC API CALLS  ***********************************************/
		/**************************************************************************************************************/
		
		/*

			
		*/
		
		protected function getAllUsers() {
			$query = "SELECT username, f_name, l_name, phone, email FROM Stomper";
			return $this->EndpointResponse($query, true);
		}
		
		

	} ## Admin_trait
?>