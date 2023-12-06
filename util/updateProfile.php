<?php

    // Include config file
    require_once "config.php";

    // Grab user parameters from session
    session_start();

    // Define variables and initialize with empty values
    $username = "{$_SESSION['username']}";

if (!empty($_POST["updateUsername"]) && !empty($_POST["updateIMG"])) {
		//update both

    $newUsername = trim($_POST["updateUsername"]);
    $newIMG = trim($_POST["updateIMG"]);

    // Prepare a insert statement
    $sql = "UPDATE User SET username = '$newUsername', profilePic = '$newIMG' WHERE username = '$username';";

    // Attempt to execute the prepared statement
    $mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
    if ($mysqli->connect_error){
	die("Connection failed: ".$mysqli->connect_error);
    }
	$stmt = $mysqli->prepare($sql);
             //Attempt to execute the prepared statement
            if($stmt->execute()){
                // Redirect to updated admin page
                header("location: profile.php");
            } else{
                echo "Oops! Something went wrong. Please try again later.";
		header("location: error.html");
            }

    $mysqli->close();
}


if (empty($_POST["updateUsername"])) {
		//just update img
    $newIMG = trim($_POST["updateIMG"]);

    // Prepare a insert statement
    $sql = "UPDATE User SET profilePic = '$newIMG' WHERE username = '$username';";

    // Attempt to execute the prepared statement
    $mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
    if ($mysqli->connect_error){
	die("Connection failed: ".$mysqli->connect_error);
    }
	$stmt = $mysqli->prepare($sql);
             //Attempt to execute the prepared statement
            if($stmt->execute()){
                // Redirect to updated admin page
                header("location: profile.php");
            } else{
                echo "Oops! Something went wrong. Please try again later.";
		header("location: error.html");
            }

    $mysqli->close();
}

if (empty($_POST["updateIMG"])) {
		//just update username
    $newUsername = trim($_POST["updateUsername"]);

    // Prepare a insert statement
    $sql = "UPDATE User SET username = '$newUsername' WHERE username = '$username';";

    // Attempt to execute the prepared statement
    $mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
    if ($mysqli->connect_error){
	die("Connection failed: ".$mysqli->connect_error);
    }
	$stmt = $mysqli->prepare($sql);
             //Attempt to execute the prepared statement
            if($stmt->execute()){
                // Redirect to updated admin page
                header("location: profile.php");
            } else{
                echo "Oops! Something went wrong. Please try again later.";
		header("location: error.html");
            }

    $mysqli->close();
}

?>