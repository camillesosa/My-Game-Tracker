<?php
    session_start();
    // Check if user is logged in
    if (!isset($_SESSION['username'])) {
        header("Location: loginlogout.php");
        exit();
    } else {
    	if($_SESSION['username'] != 'kamiyu'){
		header("Location: error.html");
        	exit();
	}

    	require_once "config.php";

    	$username = $_SESSION['username'];
    	$user_id = $_SESSION["id"];

    	$mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

    	if ($mysqli->connect_errno) {
        	printf("Connection Failed: %s\n", $mysqli->connect_error);
        	exit();
    	}
    }

?>



<!DOCTYPE html>
<html>
<head>
  <title>My Game Tracker</title>

<link rel="stylesheet" href="style.css">
</head>
<body>
    <h1 class="header">My Game Tracker</h1>
        <div class="header">
            <ul class="left_nav">
                <li><a href="home.php">Home</a></li>
                <li><a href="mylist.php">My List</a></li>
                <li><a href="achievements.html">Achievements</a></li>
                <li><a href="recommended.html">Recommended</a></li>
                <li><a href="users.php">Users</a></li>
		<li><a href="admin.php" style="border:2px solid white"><b>Admin</b></a></li>

            </ul>
            <span class="logo" style="width: 30vw;"></span>
            <ul class="right_nav">
                <li>
                </li>
                <li>
                    <form>
                        <span>
                            <input type="text" placeholder="Search...">
                            <button type="submit">Go</button>
                        </span>
                    </form>
                </li>
                <li><a href="loginlogout.php">Login/Logout</a></li>
            </ul>
        </div>
    <div class="main" style="text-align: center; color: white; margin-top: 10%">
    	<h1>Admin Page!</h1>
    	<button onclick="window.location.href = 'home.php';">Go Home</button>
    </div>
</body>
</html>