<?php

require_once "config.php";

// Make request to DB for most popular video games
$sql = "SELECT vg.game_id, vg.title, vg.coverArt, AVG(uv.rating) AS average_rating, COUNT(uv.game_id) AS occurrences FROM VideoGame vg JOIN user_videogame uv ON vg.game_id = uv.game_id GROUP BY vg.game_id, vg.title, vg.coverArt HAVING AVG(uv.rating) > 4 ORDER BY occurrences DESC LIMIT 9;";

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
                <li><a href="achievements.html">Achievements</a></li>
                <li><a href="recommended.php" style="border:2px solid white"><b>Recommended</b></a></li>
                <li><a href="users.php">Users</a></li>
            </ul>
            <span class="logo" style="width: 30vw;"></span>
            <ul class="right_nav">
                <li>
                    <select name="Sort">
                        <option value="A-Z">Sort By: A-Z</option>
                        <option value="Z-A">Sort By: Z-A</option>
                        <option value="Ranking">Sort By: Avg. Ranking</option>
                    </select>
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
        <!-- End Navigation bar -->
        <!-- Begin Page Body -->

        <div class="main">
            <!-- Left Column -->
            <h1 style="text-align: center;">Highest Rated Games Among Players</h1>
            <hr>
            <br><br>
            <div class="picture-list">
                <ul id="picture-ul">
		  <?php
                        foreach($games as $game){
                            echo "<li>";
                            echo "<img src=\"$game[coverArt]\" style=\"width: 200px;\" alt=\"$game[title]\">";
                            echo "<p>$game[title]</p>";

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
                            echo "</li>";
			}
		?>
		</ul>

            </div>
        <!-- End Page Body -->
        </div>
    </body>
</html>

