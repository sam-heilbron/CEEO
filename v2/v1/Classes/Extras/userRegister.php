/*
 *	userRegister.php
 *
 */

<?php 

	require("Conn.php");
	require("StompAPI.php");

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
	$userDetails = $api->getUserDetails($email);

	if(!empty($userDetails)) {
		$returnValue["status"] = "error";
		$returnValue["message"] = "User already exists";
		echo json_encode($returnValue);
		return;
	}

	// user password cannot be read by developer
	$secure_password = md5($password);

	$result = $api->registerUser($email,$secure_password);

	if($result) {
		$returnValue["status"] = "Success";
		$returnValue["message"] = "User is registered";
		echo json_encode($returnValue);
		return;
	}

	$api->closeConnection();

?>