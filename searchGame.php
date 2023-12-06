<?php

require_once "util/config.php";

session_start();

// Grab user input
$findGame = trim($_POST["findGame"]);

// Make request to DB for the specific video game
$sql = "SELECT vg.game_id, vg.title, vg.coverArt, AVG(uv.rating) AS average_rating
        FROM VideoGame vg
        JOIN user_videogame uv ON vg.game_id = uv.game_id
        WHERE vg.title = ?
        GROUP BY vg.game_id, vg.title, vg.coverArt;";

// Connect to DB
$mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
$dom = new DOMDocument('1.0', 'utf-8');

if ($stmt = $mysqli->prepare($sql)) {
    // Bind the parameter
    $stmt->bind_param("s", $findGame);

    // Attempt to execute prepared statement
    if ($stmt->execute()) {
        // Store result
        $stmt->store_result();
        // If one result found, return game details
        if ($stmt->num_rows == 1) {
            // Bind result variables
            $stmt->bind_result($game_id, $title, $coverArt, $avg_rating);
            $stmt->fetch();

            // Close the statement
            $stmt->close();
        } else {
            // No game found with the specified title.
            echo "Game not found.";
            header("location: error2.html");
            exit();
        }
    } else {
        echo "Oops! Something went wrong. Please try again later.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Recommended Games</title>
        <link rel="stylesheet" href="style.css">
        <style>
            .picture-list {
                max-height: 700px;
            }
            .picture-list li {
                display: inline-block;
                margin-right: 50px;
                margin-left: 100px;
                text-align: center;
                vertical-align: top;
                width: 400px;
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
        <style>
            .rating input[type="radio"] {
                display: none;
            }

            .rating label {
                display: inline-block;
                cursor: pointer;
                border-radius: 15px;
                width: 30px;
                height: 30px;
                background-image: url('https://cdn4.iconfinder.com/data/icons/ionicons/512/icon-star-512.png');
                background-size: cover;
            }

            .rating staticlabel {
                display: inline-block;
                cursor: default;
                border-radius: 15px;
                width: 30px;
                height: 30px;
                background-image: url('https://cdn4.iconfinder.com/data/icons/ionicons/512/icon-star-512.png');
                background-size: cover;
            }

                .rating input[type="radio"]:checked + label {
                background-image: url('https://cdn4.iconfinder.com/data/icons/ionicons/512/icon-star-512.png');
                background-size: cover;
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
        <h1 style="text-align: center;">Game Found</h1>
        <hr>
        <br><br>
        <div class="picture-list">
            <?php
            echo "<img src=\"$coverArt\" style=\"width: 350px;\" alt=\"$title\">";
            echo "<h2>$title</h2>";

            $ratingDiv = $dom->createElement('div');
            $ratingDiv->setAttribute('class', 'rating');
            $rating = floor($avg_rating);

            for ($i = 0; $i < 5; $i++) {
                $newLabel = $dom->createElement('staticlabel');
                $newLabel->setAttribute('style', 'cursor: default;');
                if ($i < $rating) {
                    $oldStyle = $newLabel->getAttribute('style');
                    $newLabel->setAttribute('style', $oldStyle . 'background-color: yellow;');
                }
                $ratingDiv->appendChild($newLabel);
            }

            // Convert the DOMDocument to HTML string
            $ratingHtml = $dom->saveHTML($ratingDiv);

            echo "<p>Average rating: $ratingHtml</p>";
            ?>
        </div>
    </div>
</body>
</html>