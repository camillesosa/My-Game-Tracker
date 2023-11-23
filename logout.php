<?php
    //if not logged in (after successfully logging out), redirect to login page
    if (!isset($_SESSION['username'])) {
        header("Location: login.html");
        exit();
    }

    unset($_SESSION['username']);
    $_SESSION['username'] = '';
    // Retrieve the existing value of the cookie
    $existingValue = $_COOKIE['PHPSESSID'];

    // Set the cookie expiration time to a past date
    setcookie("PHPSESSID", "", time() - 3600);

    // Display a message to confirm the update
    echo "Cookie updated successfully.";
?>