<?php

	require_once 'API_Controller.php';
	require_once 'StompAPI_types.php';
	
	abstract class StompAPI_rules extends API_Controller {
	
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
				"example" => array("string", "string", "string"),
				"me" =>array()		
			);
			
			$this->methodParamCount_POST = array(
				"getUserDetailsWithPassword" => array("string", "string"),
				"checkout" => array()
			);
		
		}
		
		protected function validateAndApplyFunction($fn, $args) {
			
			//put into a try catch statement
			
			$arr_name = "methodParamCount_" . $this->req_method;
			$a = $this->$arr_name;
			
			if (!array_key_exists ($fn , $a))
				return array("status" => "Error", "message" => "This endpoint does not accept $this->req_method requests");
				
			return $this->{$fn}($args);
			
			/**ignore below for debuggin*/
			
			$validNumArgs = (function() use ($fn, $args, $a) {return count($a[$fn]) == count($args);});
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