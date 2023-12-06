<?php

    // Include config file
    require_once "config.php";

    // Grab user parameters from session
    session_start();

    $user_id = $_SESSION["id"];
	//$user_id = 16;

    // Define variables and initialize with empty values
    $gameTitle = trim($_POST["gameTitle"]);

	// Prepare a select statement
    $sql = "SELECT game_id FROM VideoGame WHERE title = '$gameTitle';";

    // Attempt to execute the prepared statement
    $mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
    $result = $mysqli->query($sql);
    if ($result) {
    	// Fetch the associative array of the result
    	$row = $result->fetch_assoc();

    	if ($row) {
        	// Retrieve the user_id
        	$game_id = $row['game_id'];
    	} else {
        	echo "User not found";
    	}

    	// Free the result set
    	$result->free();
    } else {
    	// Handle query failure
    	echo "Query failed: " . $mysqli->error;
    }

    $mysqli->close();




    $achievement = trim($_POST["achievement"]);

    // Prepare a insert statement
    $sql = "INSERT INTO user_achievement(user_id, game_id, achievement) VALUES ('$user_id', '$game_id', '$achievement');";

    // Attempt to execute the prepared statement
    $mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
    if ($mysqli->connect_error){
	die("Connection failed: ".$mysqli->connect_error);
    }
	$stmt = $mysqli->prepare($sql);
             //Attempt to execute the prepared statement
            if($stmt->execute()){
                // Redirect to updated admin page
                header("location: achievements.php");
            } else{
                echo "Oops! Something went wrong. Please try again later.";
		header("location: error.html");
            }

    $mysqli->close();

?>