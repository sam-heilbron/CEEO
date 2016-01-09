<?php

/*
 *	Login.php
 *	Author: Sam Heilbron
 *	Last Updated: January 2016
 *
 */
 
 	require_once('vendor/autoload.php');
	use Zend\Config\Factory;
	require_once dirname(__FILE__). "/Controller/API_Controller.php";
	
	class Login extends API_Controller {	
		private $config = null;
		
		public function __construct($request, $origin) {
			$this->config = Factory::fromFile('config/config.php', true);
		
			/* Construct API_Controller */
			parent::__construct($request);
		}
		public function processLogin() {
			$username = (isset($_POST["username"])) ? htmlentities($_POST["username"]) : null;						
			$password = (isset($_POST["password"])) ? htmlentities($_POST["password"]) : null;
			if(empty($username) || empty($password)) throw new Exception("Either the username or password is empty");			
								
			return $this->_generateJWT(
				$this->_getPermissionsOfUserWithEncryptedPassword($username, SHA1($password)));

		}
		
		/* getUserDetailsWithPassword */
		private function _getPermissionsOfUserWithEncryptedPassword($u, $p) {
			$queryArray[] = "SELECT g.tid,u.uid,u.permissions FROM Stomper AS u LEFT JOIN Stomper_Team AS g USING (uid) 
								WHERE u.username = '" . $u . "' and u.pwd='" . $p . "' limit 1";
			$result = $this->implementQueryStream($queryArray);

			if ($result == null) throw new Exception("Invalid Login Credentials");
			return array("tid" => $result[0]["tid"], "uid" => $result[0]["uid"], "scope" => $result[0]["permissions"]);
		}
		
		private function _generateJWT($user) {

			$tokenId    = base64_encode(mcrypt_create_iv(32));
            $issuedAt   = time();
            $notBefore  = $issuedAt + 10;  //Adding 10 seconds
            $expire     = $this->config->get('END_OF_SEMESTER_TIME');//Expiration date. End of semester
            $serverName = $this->config->get('SERVER_NAME');
            $secretKey = base64_decode($this->config->get('JWT')->get('key'));
            $algorithm = $this->config->get('JWT')->get('algorithm');
            
            $data = [
            	'iat'  => $issuedAt,         // Issued at: time when the token was generated
                'jti'  => $tokenId,          // Json Token Id: an unique identifier for the token
                'iss'  => $serverName,       // Issuer
                'nbf'  => $notBefore,        // Not before
                'exp'  => $expire,           // Expire
                'data' => [
                	'tid' 	=> $user["tid"],	// Data related to the signer user
                	'uid' 	=> $user["uid"], 	// userid from the users table
                    'scope' => $user["scope"] 	// user scope
                ]
            ];
            
            $jwt = JWT::encode(
                        $data,      //Data to be encoded in the JWT
                        $secretKey, // The signing key
                        $algorithm  // Algorithm used to sign the token
                    );

            return json_encode(array('stomp_jwt' => $jwt)); 
		}
	} ## Login
	
?>