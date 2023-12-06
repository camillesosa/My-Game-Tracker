<?php

    // Include config file
    require_once "config.php";

    // Grab user parameters from session
    session_start();

    // Define variables and initialize with empty values
    $username = trim($_POST["username"]);
    $newUsername = trim($_POST["updateUsername"]);
    $newIMG = trim($_POST["updateIMG"]);

    // Prepare a insert statement
    $sql = "UPDATE User SET username = '$newUsername', profilePic = '$newIMG' WHERE username = '$username';";
    //$sql = "UPDATE User SET username = '$newUsername' WHERE username = '$username';";

    // Attempt to execute the prepared statement
    $mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
    if ($mysqli->connect_error){
	die("Connection failed: ".$mysqli->connect_error);
    }
	$stmt = $mysqli->prepare($sql);
             //Attempt to execute the prepared statement
            if($stmt->execute()){
                // Redirect to updated admin page
                header("location: ../admin.php");
            } else{
                echo "Oops! Something went wrong. Please try again later.";
		header("location: ../error.html");
            }

    $mysqli->close();

?>