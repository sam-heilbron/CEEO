<?php

	require_once './vendor/autoload.php';
	use Zend\Config\Factory;
	
	DEFINE('__ACTIVE_VERSION_DIR__', Factory::fromFile('config/config.php', true)->get('ACTIVE_VERSION_DIR'));
	
 	require __ACTIVE_VERSION_DIR__ . "/Constants/debug_mode.php"; /* Debug mode */
	require __ACTIVE_VERSION_DIR__ . "/Classes/SecureUser.php";

	//Confirm request
	if (!array_key_exists('request', $_REQUEST)) {
    	$_REQUEST['request'] = 'invalidEnpoint';
	}
	// Requests from the same server don't have a HTTP_ORIGIN header
	if (!array_key_exists('HTTP_ORIGIN', $_SERVER)) {
    	$_SERVER['HTTP_ORIGIN'] = $_SERVER['SERVER_NAME'];
	}
	

	try {
    	$user = SecureUser::generate($_REQUEST['request'], $_SERVER['HTTP_ORIGIN']);
    	echo $user->processAPI() . "\n";
	} catch (Exception $e) {
    	echo json_encode(Array('error' => $e->getMessage())) . "\n";
	}

?>