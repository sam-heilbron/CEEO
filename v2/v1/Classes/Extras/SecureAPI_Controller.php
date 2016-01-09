<?php

 	require_once 'vendor/autoload.php';
	use Zend\Config\Factory;
	
	require_once 'API_Controller.php';
	abstract class SecureAPI_Controller extends API_Controller {
		private $config = null;
		protected $uid = null; //user id
		protected $tid = null; //team id. Limit the return of each user to 1 team....for now
    
	    public function __construct($request) {
			/* Construct API_Controller */
			parent::__construct($request);	
    	}
    	
    	public function processAPI() {
        	if (method_exists($this, $this->endpoint)) {
    			if($this->_isAuthorized($this->endpoint))
    				return $this->_response($this->validateAndApplyFunction($this->endpoint, $this->args)); 
 	 	  	}
            return $this->_response($this->{'_invalidEndpoint'}(), 404);
    	}
    	
    	private function _isAuthorized($endpoint) {
    		$requestHeaders = apache_request_headers();
    		if (isset($requestHeaders['Authorization'])) {
    			$AUTH_HEADER = $requestHeaders['Authorization'];
				return $this->_validateJWT($AUTH_HEADER, $endpoint);	
    		}
    		return false;
    	}
    	
    	private function _validateJWT($jwt, $endpoint) {
    		try {	
    			$config = Factory::fromFile('config/config.php', true);
    			$secretKey = base64_decode($config->get('JWT')->get('key'));
    			$signingAlgorithm = base64_decode($config->get('JWT')->get('algorithm'));
    		
    			$token = JWT::decode($jwt, $secretKey, array('HS512'));
    			
    			/* Only available scope is Stomper. Expand from this point to create other Scopes */
    			/* Create scope class that handles checks of token as well as possible scopes */
    			$this->uid = $token->data->uid;
    			$this->tid = $token->data->tid;
    			return ($token->data->scope == "Stomper");
    		} catch (Exception $e) {
    			return false;
    		}
    	}
    } ## SecureAPI_Controller
?>