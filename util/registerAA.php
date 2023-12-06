<?php
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$username = $password = $email = $profilePic = "";
$username_err = $password_err = $email_err = "";

    // Prepare a select statement
    $sql = "SELECT user_id FROM User WHERE username = ?";

    if($stmt = mysqli_prepare($link, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "s", $param_username);

        // Set parameters
        $param_username = trim($_POST["new-username"]);

        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            /* store result */
            mysqli_stmt_store_result($stmt);

            if(mysqli_stmt_num_rows($stmt) == 1){
                $username_err = "This username is already taken.";
            } else{
                $username = trim(isset($_POST['new-username']) ? $_POST['new-username'] : "");
            }
        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }

    $password = trim($_POST["new-password"]);
    $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash

    $profilePic = trim($_POST["new-img"]);
    $param_profilePic = $profilePic;

    // Check input errors before inserting in database
    if(empty($username_err) && empty($password_err)){

    // Prepare a insert statement
    $sql = "INSERT INTO User (username, password, profilePic) VALUES ('$param_username', '$param_password', '$param_profilePic');";

    // Attempt to execute the prepared statement
    $mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
    if ($mysqli->connect_error){
	die("Connection failed: ".$mysqli->connect_error);
    }
	$stmt = $mysqli->prepare($sql);
             //Attempt to execute the prepared statement
            if($stmt->execute()){
                // Redirect to updated admin page
                header("location: admin.php");
            } else{
                echo "Oops! Something went wrong. Please try again later.";
		        header("location: error.html");
            }

    $mysqli->close();
    }

    // Close connection
    mysqli_close($link);
?>