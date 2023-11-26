<?php

    // Include config file
    require_once "config.php";

    // Grab user parameters from session
    session_start();

    // Define variables and initialize with empty values
    $gameTitle = trim($_POST["new-title"]);
    $img = trim($_POST["new-img"]);

    // Prepare a insert statement
    $sql = "INSERT INTO upcomingGames (title, coverArt) VALUES ('$gameTitle', '$img');";

    // Attempt to execute the prepared statement 		THIS IS BREAKING THE CODE
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
                <li><a href="admin.php">Admin</a></li>
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
                <li><a href="loginlogout.php" style="border:2px solid white"><b>Login/Logout</b></a></li>
            </ul>
        </div>
    <div class="main" style="text-align: center; color: white; margin-top: 10%">
        <h1>ERROR: Invalid username or password.</h1>
	<?php
		echo "<h4>";
		echo $gameTitle;
		echo "</h4>";
		echo "<img src=";
		echo $img;
		echo ">"
    ?>
    </div>
</body>
</html>
