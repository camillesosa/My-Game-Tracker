<?php
    session_start();
    // Check if user is logged in
    if (!isset($_SESSION['username'])) {
        header("Location: loginlogout.php");
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

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>My Achievements</title>
        <link rel="stylesheet" href="style.css">
        <style>
            .picture-list {
                overflow-y: scroll;
                max-height: 700px;
                float: left;
                width: 80%;
                min-width: 420px;
                max-width: 1500px;
            }
            .picture-list li {
                display: inline-block;
                margin-right: 10px;
                text-align: center;
                vertical-align: top;
                width: 250px;
            }
            .picture-list li p {
                margin-top: 1%;
            }
            .picture-list li img {
                width: 250px;
            }
            .picture-list li div {
                margin-bottom: 5%;
            }
        </style>
    </head>
    <body>
        <!-- Begin navigation bar. -->
        <h1 class="header">My Game Tracker</h1>
        <div class="header">
            <ul class="left_nav">
                <li><a href="home.php">Home</a></li>
                <li><a href="mylist.php">My List</a></li>
                <li><a href="achievements.php" style="border:2px solid white"><b>Achievements</b></a></li>
                <li><a href="recommended.php">Recommended</a></li>
                <li><a href="users.php">Users</a></li>
		<?php
		session_start();
		if($_SESSION['username'] === 'admin'){
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
		<form name="orderGames" method="post">
                    <select name="Sort" onchange="orderGames.submit()">
			<option value="null">Sort By...</option>
                        <option value="A-Z">Sort By: A-Z</option>
                        <option value="Z-A">Sort By: Z-A</option>
                    </select>
		</form>
		<?php
			session_start();
			if(isset($_POST['Sort'])) {
				$sort = $_POST['Sort'];
				if($sort == "A-Z"){
					//sort by name
					$sql = "SELECT user_videogame.game_id, VideoGame.title FROM user_videogame JOIN VideoGame ON user_videogame.game_id = VideoGame.game_id WHERE user_videogame.user_id = '$user_id' ORDER BY VideoGame.title;";
				} else if($sort == "Z-A"){
					//sort by name backwards
					$sql = "SELECT user_videogame.game_id, VideoGame.title FROM user_videogame JOIN VideoGame ON user_videogame.game_id = VideoGame.game_id WHERE user_videogame.user_id = '$user_id' ORDER BY VideoGame.title DESC;";
				} else {
					//sort by # of trophies
					$sql = "SELECT user_videogame.game_id, VideoGame.title FROM user_videogame JOIN VideoGame ON user_videogame.game_id = VideoGame.game_id WHERE user_videogame.user_id = '$user_id';";
				}
			} else {
				$sql = "SELECT user_videogame.game_id, VideoGame.title FROM user_videogame JOIN VideoGame ON user_videogame.game_id = VideoGame.game_id WHERE user_videogame.user_id = '$user_id';";
			}

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
           					$stmt->bind_result($game_id, $gameTitle);
            					$games = array();
            					while($stmt->fetch()){
                					$games[] = array("game_id" => $game_id, "gameTitle" => $gameTitle);

                					$games[count($games)-1]["game_id"] = $game_id;
                					$games[count($games)-1]["gameTitle"] = $gameTitle;
            					}
        				} else{
            					// echo "No games found.";
        				}
    				} else{
        				// echo "Oops! Something went wrong. Please try again later.";
    				}
    				// Close statement
    				$stmt->close();

		} ?>

                </li>
                <li>
                    <form action="searchGame.php" method="post">
                        <span>
                            <input type="text" id="findGame" name="findGame" placeholder="Search a game...">
                            <button type="submit">Go</button>
                        </span>
                    </form>
                </li>
                <li><?php
			if (isset($_SESSION['username'])) {
				echo "<a href='loginlogout.php'>Logout</a>";
			} else{
				echo "<a href='loginlogout.php'>Login</a>";
			}
		    ?>
		</li>
            </ul>
        </div>
        <!-- End Navigation bar -->
        <!-- Begin Page Body -->

        <div class="main">
            <!-- Left Column -->
            <div style="float: left; width: 80%; overflow-y: scroll; max-height: 700px">
                <?php
			foreach($games as $game){
    				$mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

    				if ($mysqli->connect_errno) {
       					printf("Connection Failed: %s\n", $mysqli->connect_error);
        				exit();
    				}

    				$sql = "SELECT achievement FROM user_achievement WHERE user_id = '$user_id' AND game_id = $game[game_id];";
    				$achievements = array();
				if($stmt = $mysqli->prepare($sql)){
    					// Attempt to execute prepared statement
    					if($stmt->execute()){
        					// Store result
        					$stmt->store_result();
        					// If more than one result, return array of games
        					if($stmt->num_rows >= 1){
            						// Bind result variables
            						$stmt->bind_result($achievement);
            						//$achievements = array();
            						while($stmt->fetch()){
                						$achievements[] = array("achievement" => $achievement);

                						$achievements[count($achievements)-1]["achievement"] = $achievement;
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



				echo "<div class='gallery'>";
				echo "<h2>$game[gameTitle]</h2>";
				//echo "<h2>$game[game_id]</h2>";
				echo "<div class='scrolling-list' style='border: 5px solid #202225;'>";
				foreach($achievements as $achievement){
					echo "<div>
                            			<img src='img/trophiesIcon.png' style='width: 200px;' alt='Game 1'>
                            			<p>$achievement[achievement]</p>
                        	      	      </div>";
				}
				echo "</div></div>";
			}
		?>

            </div>

            <!-- Right Column -->
            <div style="justify-content: center; width: auto; text-align: center;" class="container">
                <div>
                    <br><br><br>
		    <img style="padding-top: 10%; width: 120px; height: 120px;" src="img/trophy.png" alt="Picture 11"><br><br>
                    <div class="text-entry" style="height: 200px;">
                        <form action="addAchievement.php" method="post">
                            <h2 style="color: #fff;">Add a new achievement</h2>
                            <label style="padding-top: 10%;" for="gameTitle">Game:</label>
                            <select name="gameTitle" style="width: 170px; background-color: #292b2f; color: #fff; border-color: #292b2f; border-radius: 10px; padding: 5px; margin-right: 5px;">
				<?php
    					foreach ($games as $game) {
        					echo "<option style='color: #fff;' value=\"$game[gameTitle]\">$game[gameTitle]</option>";
    					}
    				?>
			    </select>
                            <label style="padding-top: 10%;" for="achievement">Achievement:</label>
                            <input type="text" id="achievement" name="achievement">

                            <button type="submit">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        <!-- End Page Body -->
        </div>
    </body>
</html>

