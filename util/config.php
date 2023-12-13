<?php
/* Database credentials. Assuming you are running MySQL
server with default setting (user 'root' with no password) */
// TODO change to docker container name
define('DB_SERVER', 'my-game-tracker-mygamedb-1');
define('DB_USERNAME', 'greenteam');
define('DB_PASSWORD', 'ra8t9kw8');
define('DB_NAME', 'greenteam');

/* Attempt to connect to MySQL database */
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if($link === false){
    echo "Failed with params: " . DB_SERVER . " " . DB_USERNAME . " " . DB_PASSWORD . " " . DB_NAME . " 3306";
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
?>