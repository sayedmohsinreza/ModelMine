<?php

	//---------------Change this 4 Information -------//
	$host = 'localhost';
	$user = 'root';
	$password = '';
	$db = 'github_miners';
	//---------------Change this 4 Information -------//



	//---------------MySQLi Connection -------//
	$conn = new MySQLi($host, $user, $password, $db);
	if ($conn->connect_error) {
    	die("Connection failed: " . $conn->connect_error);
	} else {
		error_log("successfully connected to database");
	}
	//---------------MySQLi Connection -------//
?>