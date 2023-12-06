<?php

require_once "util/config.php";

// Make request to DB for most popular video games
$sql = "SELECT vg.game_id, vg.title, vg.coverArt, AVG(uv.rating) AS average_rating, COUNT(uv.game_id) AS occurrences FROM VideoGame vg JOIN user_videogame uv ON vg.game_id = uv.game_id GROUP BY vg.game_id, vg.title, vg.coverArt ORDER BY occurrences DESC LIMIT 7;";

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
            $stmt->bind_result($game_id, $title, $coverArt, $avg_rating, $occurrences);
            $games = array();
            while($stmt->fetch()){
                $games[] = array("game_id" => $game_id, "title" => $title, "coverArt" => $coverArt, "avg_rating" => $avg_rating);
                
                $games[count($games)-1]["avg_rating"] = $avg_rating;
                $games[count($games)-1]["coverArt"] = $coverArt;
                $games[count($games)-1]["title"] = $title;
            
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

// Make request to DB for most popular video games
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
            $upcoming = array();
            while($stmt->fetch()){
                $upcoming[] = array("title" => $title, "coverArt" => $coverArt);

                $upcoming[count($upcoming)-1]["coverArt"] = $coverArt;
                $upcoming[count($upcoming)-1]["title"] = $title;

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
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>My Game Tracker</title>
        <link rel="stylesheet" href="style.css">
		<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    </head>
    <body>
	<style>
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

	    .scrolling-list {
	        display: flex;
    		overflow-x: scroll;
    		scroll-behavior: smooth;
    		background-color: #292b2f;
    		padding: 3%;
	    }

	    .scrolling-list h4 {
    		text-align: center;
    		color: white;
	    }

	    .scrolling-list img {
    		max-width: 200px;
    		padding: 5%;
	    }

	    .scrolling-list p {
    		text-align: center;
	    }
        </style>
        <h1 class="header">My Game Tracker</h1>
        <div class="header">
            <ul class="left_nav">
                <li><a href="home.php" style="border:2px solid white"><b>Home</b></a></li>
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
        <div class="main">
            <div style="float: left; width: 60%;">
                <div class="gallery">
                    <h2>Trending Games</h2>
                    <div tag="scrolling-list" class="scrolling-list">
                        <?php
                        foreach($games as $game){
                            echo "<span>";
                            echo "<img src=\"$game[coverArt]\" style=\"width: 200px;\" alt=\"$game[title]\">";
                            echo "<h4>$game[title]</h4>";

                            $ratingDiv = $dom->createElement('div');
                            $ratingDiv->setAttribute('class', 'rating');
                            $rating = floor($game['avg_rating']);

                            for ($i = 0; $i < 5; $i++){
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

                            echo $ratingHtml;

                            echo "</span>";
                        }
                        ?>
                    </div>
                </div>
                <div class="gallery">
                    <h2>Upcoming Games</h2>
                    <div class="scrolling-list upcoming-scrolling-list">
                        <?php
                        foreach($upcoming as $upcomingG){
                            echo "<span>";
                            echo "<img src=\"$upcomingG[coverArt]\" style=\"width: 200px;\" alt=\"$game[title]\">";
                            echo "<h4>$upcomingG[title]</h4>";
			    echo "</span>";
			}
			?>
                    </div>
                </div>
            </div>
            <div style="float: right; width: 37%;">
                <div class="trending-topics">
                    <h2 style="text-align: center">Trending Topics</h2><br><hr><br>
                    <ul style="list-style-type: none;">
                        <li><a href="article1.html">Grand Theft Auto 8 Unleashed: A Fictional Gaming Spectacle</a></li><br>
                        <li><a href="article2.html">Cybernetic Odyssey: Exploring the Virtual Realms of 'NexaSphere' - A Futuristic Gaming Marvel</a></li><br>
                        <li><a href="article3.html">Legends Rise: Unveiling the Mythical Realms in 'EpicQuest' - A Fantasy Adventure Extravaganza</a></li><br>
                        <li><a href="article4.html">Quantum Conundrum: Delving into the Mind-Bending Puzzles of 'TechnoMaze' - A Puzzle Masterpiece</a></li><br>
			<li><a href="article5.html">Starship Commanders: Navigating the Cosmos in 'Galactic Conquest' - An Intergalactic Strategy Epic</a></li><br>
                    </ul>
                </div>
            </div>
        </div>
		<script>
			$(document).ready(function () {
				var scrollSpeed = 2; 
				var scrollingList = $('.upcoming-scrolling-list');
				scrollingList.append(scrollingList.html());
				function scrollList() {
					scrollingList.scrollLeft(scrollingList.scrollLeft() + scrollSpeed);
				}
				var scrollInterval = setInterval(scrollList, 30);
				scrollingList.on('mouseover', function () {
					clearInterval(scrollInterval);
				});
				scrollingList.on('mouseout', function () {
					scrollInterval = setInterval(scrollList, 30);
				});
			});
		</script>
    </body>
</html>
