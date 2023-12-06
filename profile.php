<?php
    session_start();

    	require_once "util/config.php";

    	$username = $_SESSION['username'];
    	$user_id = $_SESSION["id"];

    	$mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

    	if ($mysqli->connect_errno) {
        	printf("Connection Failed: %s\n", $mysqli->connect_error);
        	exit();
    	}

require_once "config.php";

// Connect to DB
$mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
$dom = new DOMDocument('1.0', 'utf-8');

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
                <li><a href="achievements.php">Achievements</a></li>
                <li><a href="recommended.php">Recommended</a></li>
                <li><a href="users.php">Users</a></li>
                <?php
		        session_start();
		        if(isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'] == true){
			        echo "<li><a href='admin.php'>Admin</a></li>";
		        } ?>
            </ul>
            <span class="logo" style="width: 30vw;"></span>
            <ul class="right_nav">
	    <div style="position: fixed; top: 0; right: 0; transform: translate(-100%, 0); background-color: #292b2f; color: #fff; padding: 5px; border-radius: 5px;">
		<?php
			session_start();

			if (isset($_SESSION['id'])) {
				$userID = "{$_SESSION['id']}";
				$mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

				$query = "SELECT username, profilePic FROM User WHERE user_id = '$userID';";
    				$result = $mysqli->query($query);
				if ($result) {
    					// Fetch the associative array of the result
    					$row = $result->fetch_assoc();

    					if ($row) {
        					// Retrieve the username and profilePic
						$username = $row['username'];
        					$profilePic = $row['profilePic'];
    					} else {
        					echo "Picture not found";
    					}
				}
				echo "<a href='profile.php'>$username </a>";
				echo "<img src='$profilePic' style='width: 50px;'>";
				$mysqli->close();
    			}
		?>
	    </div>
                <li>
                </li>
                <li>
                    <form action="searchGame.php" method="post">
                        <span>
                            <input type="text" id="findGame" name="findGame" placeholder="Search a game...">
                            <button type="submit">Go</button>
                        </span>
                    </form>
                </li>
                <li><a href="loginlogout.php">Logout</a></li>
            </ul>
        </div>
    <div class="main" style="text-align: center; color: white; margin-top: 2%">
    	<h1>Your Profile</h1>
	<hr>
    </div>

<div class="main">
  <div style="float: left; width: 60%; text-align: center;">

    <div class="gallery">
	<div>
		<?php
			    echo "<img src='$profilePic'>";
			    echo "<br><br>";
                            echo "<h2>$username</h2>";
		?>
	</div>
    </div>

  </div>
</div>

<!-- Right Column -->
            <div style="justify-content: left; width: auto; text-align: center;" class="container">
		<div>
                        <form action="util/updateProfile.php" method="post">
                            <h2 style="color: #fff;">Update profile</h2>
			    <label style="padding-top: 10%;" for="updateUsername">Change username:</label>
                            <input type="text" id="updateUsername" name="updateUsername">
                            <label style="padding-top: 10%;" for="updateIMG">Change picture:</label>
                            <input type="text" id="updateIMG" name="updateIMG">
                            <button type="submit">Submit</button>
                        </form>
                </div>

		<div>
                        <form action="util/deleteUser.php" method="post">
                            <h2 style="color: #fff;">Delete profile</h2>
                            <label style="padding-top: 10%;" for="deleteUser">Enter password:</label>
                            <input type="text" id="deleteUser" name="deleteUser">
                            <button type="submit">Submit</button>
                        </form>
                </div>

            </div>


</body>
</html>