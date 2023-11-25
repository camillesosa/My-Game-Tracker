<?php 

if($_SERVER["REQUEST_METHOD"] == "POST"){

    
    // Include config file
    require_once "config.php";

    // Grab user parameters from session
    session_start();
    $user_id = $_SESSION["id"];

    // Accept game and parameters from form
    $game_id = $_POST['gameId'];

    // Get review based on how many stars selected
    $gameRating = $_POST['rating'];

    $gameReview = $_POST['reviewText'];

    // Prepare an insert statement ()
    $sql = "INSERT INTO user_videogame(user_id, game_id, rating, review) VALUES ($user_id, $game_id, $gameRating, '$gameReview');";

    echo $sql;

    // Attempt to execute the prepared statement
    $mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
    if($mysqli->connect_errno){
        echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    }else if($mysqli->query($sql) === true){
        // Redirect to updated myList
        header("location: mylist.php");
    } else{
        echo "ERROR: Could not execute query: $sql. " . $mysqli->error;
    }
    $mysqli->close();
}
?>