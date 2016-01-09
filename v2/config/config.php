<?php

	return array(
    	'JWT' => array(
    		/* Key for signing the JWT's, base64_encode(openssl_random_pseudo_bytes(64)) */
        	'key'       => '76ykWyfTsDfwDsG13lnw7fNLiYAjFmZldF1yTg1NtEODp2K1PT/+2gh7dBXdcK0vvmikjXcoPqLj8e1QeYzEYQ==',
        	 /* 
        		PHP JWT supported signing algorithms:
        			'HS256' => array('hash_hmac', 'SHA256'),
        			'HS512' => array('hash_hmac', 'SHA512'),
        			'HS384' => array('hash_hmac', 'SHA384'),
        			'RS256' => array('openssl', 'SHA256'),
        	 */
        	'algorithm' => 'HS512' 
        ),
        'ACTIVE_VERSION_DIR' => 'v1',
        'END_OF_SEMESTER_TIME' => '1464739200', //EPOCH time
        'ACTIVE_DB' => 'TESTING_DB', /* Set current Database to connect to. Used by Conn class */
    	'PRODUCTION_DB' => array(
        	'user'     => '', // Database username
        	'password' => '', // Database password
        	'host'     => '', // Database host
        	'name'     => '', // Database schema name
    	),
    	'TESTING_DB' => array(
    	    'user'     => 'samh', // Database username
        	'password' => 'Tufts2016', // Database password
        	'host'     => 'localhost', // Database host
        	'name'     => 'STOMP_app', // Database schema name
        ),
    	'SERVER_NAME' => 'localhost',
	);

?>
