/*
 *	userLogin.php
 *
 */

<?php

	require("Classes/Conn.php");
	require("Classes/StompAPI.php");

	$email = htmlentities($_POST["email"]);
	$password = htmlentities($_POST["password"]);
	$returnValue = array();

	if(empty($email) || empty($password)) {
		$returnValue["status"] = "error";
		$returnValue["message"] = "Missing required field";
		echo json_encode($returnValue);
		return;
	}

	$api = new StompAPI();
	$api->openConnection();
	$userDetails = $api->getUserDetailsWithPassword($email, md5($password));

	if(!empty($userDetails)) {
		$returnValue["status"] = "Success";
		$returnValue["message"] = "User is registered";
	} else {
		$returnValue["status"] = "error";
		$returnValue["message"] = "User is not found";
	}
	echo json_encode($returnValue);

	$api->closeConnection();

?>