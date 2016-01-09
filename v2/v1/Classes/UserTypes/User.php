<?php

/*  
Basic User class.
Middleware for Classes. 1 function handles call to endpoint and otherhandles response
*/

	require_once dirname(__FILE__). "/../Controller/API_Controller.php";
	
	abstract class User extends API_Controller {
		protected $uid = null;
		protected $tid = null;

		public function __construct($request, $uid, $tid = null) {
			$this->uid = $uid;
			$this->tid = $tid;
		
			/* Construct API_Controller */
			parent::__construct($request);
		}
		
		protected function validateAndApplyFunction($fn, $args) {
			return $this->{$fn}($args);
		}
		
		protected function EndpointResponse($queryStream, $show_result = false) {
			try {
				$response = $this->implementQueryStream($queryStream);
				return ($show_result) ? $response : $this->genericSuccess();
			} catch (Exception $e) {
				throw $e;
			}
		}

	} ## User
?>