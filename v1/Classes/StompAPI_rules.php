<?php

	require_once 'API_Controller.php';
	require_once 'StompAPI_types.php';
	
	class StompAPI_rules extends API_Controller {
	
		private $methodParamCount_GET = null;
		private $methodParamCount_POST = null;
		
		public function __construct($request) {
			$this->__constructArrays();
			
			/* Construct API_Controller */
			parent::__construct($request);
		}
		private function __constructArrays() {
			/*echo "Initialize function names with set parameter requirements";*/
			
			$this->methodParamCount_GET = array(
				"getAllUsers" => array(),
				"getAllMaterials" => array(),
				"example" => array("string", "string", "string")		
			);
			
			$this->methodParamCount_POST = array(
				"getUserDetailsWithPassword" => array(2, "string", "string")
			);
		
		}
		
		protected function validateAndApplyFunction($fn, $args) {
			//Debug. 
			return $this->{$fn}($args);
			
			
			$arr_name = "methodParamCount_" . $this->req_method;
			$a = $this->$arr_name;
			
			if (!array_key_exists ($fn , $a))
				return array("status" => "Error", "message" => "This endpoint does not accept $this->req_method requests");
			
			$validNumArgs = (function() use ($fn, $args, $a) {
				return count($a[$fn]) == count($args) ? true : false;
			});
			if(!$validNumArgs()) 
				return array("status" => "Error", "message" => "Invalid Number of Arguments");
			
			$validIndArgs = (function() use ($fn, $args, $a) {
				for ($i = 0; $i < count($args); $i++) {
					if(gettype($args[$i]) != $a[$fn][$i]) return false;
				}
				return true;
			});
			if(!$validIndArgs()) 
				return array("status" => "Error", "message" => "One of the arguments is invalid. Please check the documentation.");
			else 
				return $this->{$fn}($args);
		}

		
	} ## StompAPI_rules
?>