<?php

require_once "config.php";

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
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>My Game Tracker</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <h1 class="header">My Game Tracker</h1>
        <div class="header">
            <ul class="left_nav">
                <li><a href="home.html" style="border:2px solid white"><b>Home</b></a></li>
                <li><a href="mylist.html">My List</a></li>
                <li><a href="achievements.html">Achievements</a></li>
                <li><a href="recommended.html">Recommended</a></li>
                <li><a href="users.html">Users</a></li>
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
                <li><a href="login.html">Login</a></li>
            </ul>
        </div>
        <div class="main">
            <div style="float: left; width: 60%;">
                <div class="gallery">
                    <h2>Trending Games</h2>
                    <div tag="scrolling-list" class="scrolling-list">
                        <?php 
                        foreach($games as $game){
                            echo "<div>";
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

                            echo "</div>";
                        }
                        ?>
                    </div>
                </div>
                <div class="gallery">
                    <h2>Upcoming Games</h2>
                    <div class="scrolling-list">
                        <div>
                            <img src="img/soulsilver.bmp" style="width: 200px;" alt="Game 1">
                            <p>Game 1</p>
                        </div>
                        <div>
                            <img src="img/soulsilver.bmp" style="width: 200px;" alt="Game 1">
                            <p>Game 2</p>
                        </div>
                        <div>
                            <img src="img/soulsilver.bmp" style="width: 200px;" alt="Game 1">
                            <p>Game 3</p>
                        </div>
                        <div>
                            <img src="img/soulsilver.bmp" style="width: 200px;" alt="Game 1">
                            <p>Game 4</p>
                        </div>
                        <div>
                            <img src="img/soulsilver.bmp" style="width: 200px;" alt="Game 1">
                            <p>Game 5</p>
                        </div>
                    </div>
                </div>
            </div>
            <div style="float: left; width: 40%;">
                <div class="trending-topics">
                    <h2>Trending Topics</h2>
                    <ul>
                        <li><a href="#">The excitinue thready felt the open my brought the oncentally kind of unressed sides, I neven my contive traded by to colleave the end</a></li>
                        <li><a href="#">That missing to see a bummer, and it because extra in plot that because end</a></li>
                        <li><a href="#">New part one, is never one, and as that because ever 87 how threads any markers, any missed by to that's already full of this new Game+.</a></li>
                        <li><a href="#">New Game+. The to New Game+. </a></li>
                    </ul>
                </div>
            </div>
        </div>
    </body>
</html>