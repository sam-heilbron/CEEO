README.txt

 /**
 
 
 			$this->_app_id = $app_id; (private var)
        	$this->_app_key = $app_key;
        	$this->_api_url = $api_url;
        	
        	new api key and user???
        	
        	
        	if (!array_key_exists('apiKey', $this->request)) {
            	throw new Exception('No API Key provided');
        	} else if (!$APIKey->verifyKey($this->request['apiKey'], $origin)) {
            	throw new Exception('Invalid API Key');
        	} else if (array_key_exists('token', $this->request) && !$User->get('token', $this->request['token'])) {
	            throw new Exception('Invalid User Token');
    	    }
 **/
