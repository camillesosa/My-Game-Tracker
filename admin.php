<?php
    session_start();
    // Check if user is logged in
    if (!isset($_SESSION['username'])) {
        header("Location: loginlogout.php");
        exit();
    } else {
    	if($_SESSION['username'] != 'admin'){
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

require_once "config.php";
//for upcomingGames
// Make request to DB for umpcoming video games
$sql = "SELECT title, coverArt FROM upcomingGames;";

// Connect to DB
$mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
$dom = new DOMDocument('1.0', 'utf-8');


if($stmt = $mysqli->prepare($sql)){
    // Attempt to execute prepared statement
    if($stmt->execute()){
        // Store result
        $stmt->store_result();
        // If more than one result, return array of games
        if($stmt->num_rows >= 1){
            // Bind result variables
            $stmt->bind_result($title, $coverArt);
            $upcomingGs = array();
            while($stmt->fetch()){
                $upcomingGs[] = array("Utitle" => $title, "UcoverArt" => $coverArt);

                $upcomingGs[count($upcomingGs)-1]["UcoverArt"] = $coverArt;
                $upcomingGs[count($upcomingGs)-1]["Utitle"] = $title;
            }
        } else{
            // echo "No games found.";
	}
    } else{
        // echo "Oops! Something went wrong. Please try again later.";
    }
    // Close statement
    $stmt->close();
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
                <li><a href="recommended.php">Recommended</a></li>
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
    <div class="main" style="text-align: center; color: white; margin-top: 5%">
    	<h1>Admin Page!</h1>
    </div>

<div class="main">
  <div style="float: left; width: 60%;">

    <div class="gallery">
    	<h2>Upcoming Games</h2>
	<div class="scrolling-list">
		<?php
		foreach($upcomingGs as $upcoming){
                            echo "<span>";
                            echo "<img src=\"$upcoming[UcoverArt]\" style=\"width: 200px;\" alt=\"$upcoming[title]\">";
                            echo "<h4>$upcoming[Utitle]</h4>";
                            echo "</span>";
		}
		?>
	</div>
    </div>
  </div>
</div>

<!-- Right Column -->
            <div style="justify-content: center; width: auto; text-align: center;" class="container">
                <div>
                        <form action="upcomingGame.php" method="post">
                            <h2 style="color: #fff;">Add a game</h2>
                            <label style="padding-top: 10%;" for="new-title">Title:</label>
                            <input type="text" id="new-title" name="new-title">
                            <label style="padding-top: 10%;" for="new-img">Img link:</label>
                            <input type="text" id="new-img" name="new-img">
                            <button type="submit">Submit</button>
                        </form>
                </div>
		<div>
                        <form action="updateGame.php" method="post">
                            <h2 style="color: #fff;">Update a game</h2>
                            <label style="padding-top: 10%;" for="title">Title:</label>
                            <input type="text" id="title" name="title">
			    <label style="padding-top: 10%;" for="updateTitle">New title:</label>
                            <input type="text" id="updateTitle" name="updateTitle">
                            <label style="padding-top: 10%;" for="updateIMG">New img link:</label>
                            <input type="text" id="updateIMG" name="updateIMG">
                            <button type="submit">Submit</button>
                        </form>
                </div>
		<div>
                        <form action="deleteGame.php" method="post">
                            <h2 style="color: #fff;">Delete a game</h2>
                            <label style="padding-top: 10%;" for="deleteGame">Title:</label>
                            <input type="text" id="deleteGame" name="deleteGame">
                            <button type="submit">Submit</button>
                        </form>
                </div>

            </div>



</body>
</html>