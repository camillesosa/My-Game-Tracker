<?php
/* Database credentials. Assuming you are running MySQL
server with default setting (user 'root' with no password) */
// TODO change server to docker container name
// TODO spin up dedicated FlyIO app for DB
// Dev env will be : compose two containers on local machine, one for DB, one for app
// Prod env will be : One FlyIO app for DB (2GB RAM), One FlyIO app for app (256MB RAM)
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