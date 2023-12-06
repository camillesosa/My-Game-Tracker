<?php

// Include config file
require_once "config.php";

// Grab user parameters from session
session_start();

// Define variables and initialize with empty values
$username = "{$_SESSION['username']}";

if (!empty($_POST["deleteUser"])) {
    // Check password and, if correct, then delete the user
    $mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

    $checkPassword = trim($_POST["deleteUser"]);

    // Grab the saved password (hashed) from the database
    $query = "SELECT password FROM User WHERE username = '$username';";
    $result = $mysqli->query($query);

    if ($result) {
        // Fetch the associative array of the result
        $row = $result->fetch_assoc();

        if ($row) {
            // Retrieve the hashed password
            $hashedPassword = $row['password'];
        } else {
            echo "Password not found";
        }

        $mysqli->close();
    }

    // Check password vs saved password here
    if (password_verify($checkPassword, $hashedPassword)) {
        // Password is correct, delete the user

        // Prepare a delete statement
        $sql = "DELETE FROM User WHERE username = '$username';";

        // Attempt to execute the prepared statement
        $mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

        if ($mysqli->connect_error) {
            die("Connection failed: " . $mysqli->connect_error);
        }

        $stmt = $mysqli->prepare($sql);

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            // Redirect to updated login
            setcookie('PHPSESSID', '', -1, '/');
            header("location: home.php");
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }

        $mysqli->close();
    } else {
        // Password is incorrect, redirect to the profile page
        header("location: profile.php");
    }
} else {
    // Redirect to the profile page if delete password is not provided
    header("location: profile.php");
}
?>