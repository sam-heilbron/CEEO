<?php

/*
 *	userLogin.php
 *
 */
	require_once './vendor/autoload.php';
	use Zend\Config\Factory;
	
	DEFINE('__ACTIVE_VERSION_DIR__', Factory::fromFile('config/config.php', true)->get('ACTIVE_VERSION_DIR'));
	
 	require __ACTIVE_VERSION_DIR__ . "/Constants/debug_mode.php"; /* Debug mode */
	require __ACTIVE_VERSION_DIR__ . "/Classes/Login.php";
	
	
	//catch case that is missed by .htaccess --> /Stomp/ (where there is no request)
	if (!array_key_exists('request', $_REQUEST)) {
    	$_REQUEST['request'] = 'invalidEnpoint';
	}
	// Requests from the same server don't have a HTTP_ORIGIN header
	if (!array_key_exists('HTTP_ORIGIN', $_SERVER)) {
    	$_SERVER['HTTP_ORIGIN'] = $_SERVER['SERVER_NAME'];
	}

	try {
    	$login = new Login($_REQUEST['request'], $_SERVER['HTTP_ORIGIN']);
    	echo $login->processLogin() . "\n";
	} catch (Exception $e) {
    	echo json_encode(Array('error' => $e->getMessage())) . "\n";
	}
	
?>