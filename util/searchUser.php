<?php

	session_start();
	require_once "config.php";

	$findUser = trim($_POST["findUser"]);

	$mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

    	if ($mysqli->connect_errno) {
        	printf("Connection Failed: %s\n", $mysqli->connect_error);
        	exit();
    	}
	$dom = new DOMDocument('1.0', 'utf-8');

	$query = "SELECT username, user_id FROM User WHERE username = '$findUser';";
    	$result = $mysqli->query($query);
				if ($result) {
    					// Fetch the associative array of the result
    					$row = $result->fetch_assoc();

    					if ($row) {
        					// Retrieve the username and user_id
						$username = $row['username'];
        					$user_id = $row['user_id'];

						header("location: ../selectedUserList.php?username=" . urlencode($username));
    						exit();
    					} else {
        					echo "User not found";
						header("location: ../error3.html");
    					}
				}
?>