<?php
    session_start();
    // Check if user is logged in
    if (!isset($_SESSION['username'])) {
        header("Location: login.php");
        exit();
    }
    $username = $_SESSION['username'];
    $user_id = $_SESSION["id"];

    $mysqli = new mysqli('localhost', 'root', 'password', 'greenteam');

    if ($mysqli->connect_errno) {
        printf("Connection Failed: %s\n", $mysqli->connect_error);
        exit();
    }

    $query = "SELECT user_achievements.game_ID, user_achievements.title, user_achievements.achievement_ID from user_achievements join achievements on user_achievements.achievement_ID = achievements.achievement_ID where user_achievements.user_id = '$user_id';";

    $result = $mysqli->query($query);
    if (!$result) {
        printf("Query failed: %s\n", $mysqli->error);
        exit();
    }
    $mysqli->close();
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
                <li><a href="home.html">Home</a></li>
                <li><a href="mylist.php">My List</a></li>
                <li><a href="achievements.php" style="border:2px solid white"><b>Achievements</b></a></li>
                <li><a href="recommended.html">Recommended</a></li>
                <li><a href="users.html">Users</a></li>
            </ul>
            <span class="logo" style="width: 30vw;"></span>
            <ul class="right_nav">
                <li>
                    <select name="Sort">
                        <option value="A-Z">Sort By: A-Z</option>
                        <option value="Z-A">Sort By: Z-A</option>
                        <option value="# of trophies">Sort By: # of Trophies</option>
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
                <li><a href="login.php">Login</a></li>
            </ul>
        </div>
        <!-- End Navigation bar -->
        <!-- Begin Page Body -->

        <div class="main">
            <!-- Left Column -->

            <div style="float: left; width: 80%; overflow-y: scroll; max-height: 700px">
                <div class="gallery">
                    <h2>Game 1</h2>
                    <div class="scrolling-list" style="border: 5px solid white;">
                        <div>
                            <img src="img/trophiesIcon.png" style="width: 200px;" alt="Game 1">
                            <p>Achievement 1</p>
                        </div>
                        <div>
                            <img src="img/trophiesIcon.png" style="width: 200px;" alt="Game 1">
                            <p>Achievement 2</p>
                        </div>
                        <div>
                            <img src="img/trophiesIcon.png" style="width: 200px;" alt="Game 1">
                            <p>Achievement 3</p>
                        </div>
                        <div>
                            <img src="img/trophiesIcon.png" style="width: 200px;" alt="Game 1">
                            <p>Achievement 4</p>
                        </div>
                        <div>
                            <img src="img/trophiesIcon.png" style="width: 200px;" alt="Game 1">
                            <p>Achievement 5</p>
                        </div>
                        <div>
                            <img src="img/trophiesIcon.png" style="width: 200px;" alt="Game 1">
                            <p>Achievement 6</p>
                        </div>
                    </div>
                </div>
                <div class="gallery">
                    <h2>Game 2</h2>
                    <div class="scrolling-list" style="border: 5px solid white;">
                        <div>
                            <img src="img/trophiesIcon.png" style="width: 200px;" alt="Game 1">
                            <p>Achievement 1</p>
                        </div>
                        <div>
                            <img src="img/trophiesIcon.png" style="width: 200px;" alt="Game 1">
                            <p>Achievement 2</p>
                        </div>
                        <div>
                            <img src="img/trophiesIcon.png" style="width: 200px;" alt="Game 1">
                            <p>Achievement 3</p>
                        </div>
                    </div>
                </div>
                <div class="gallery">
                    <h2>Game 3</h2>
                    <div class="scrolling-list" style="border: 5px solid white;">
                        <div>
                            <img src="img/trophiesIcon.png" style="width: 200px;" alt="Game 1">
                            <p>Achievement 1</p>
                        </div>
                        <div>
                            <img src="img/trophiesIcon.png" style="width: 200px;" alt="Game 1">
                            <p>Achievement 2</p>
                        </div>
                        <div>
                            <img src="img/trophiesIcon.png" style="width: 200px;" alt="Game 1">
                            <p>Achievement 3</p>
                        </div>
                        <div>
                            <img src="img/trophiesIcon.png" style="width: 200px;" alt="Game 1">
                            <p>Achievement 4</p>
                        </div>
                        <div>
                            <img src="img/trophiesIcon.png" style="width: 200px;" alt="Game 1">
                            <p>Achievement 5</p>
                        </div>
                        <div>
                            <img src="img/trophiesIcon.png" style="width: 200px;" alt="Game 1">
                            <p>Achievement 6</p>
                        </div>
                        <div>
                            <img src="img/trophiesIcon.png" style="width: 200px;" alt="Game 1">
                            <p>Achievement 7</p>
                        </div>
                        <div>
                            <img src="img/trophiesIcon.png" style="width: 200px;" alt="Game 1">
                            <p>Achievement 8</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div style="justify-content: center; width: auto; text-align: center;" class="container">
                <div>
                    <div class="search-box" style="height: 200px;">
                        <form>
                            <input type="text" placeholder="Search your achievements...">
                            <button type="submit">Go</button>
                        </form>
                        <img style="padding-top: 10%; width: 120px; height: 120px;" src="img/trophy.png" alt="Picture 11"><p>Caption 11</p>
                    </div>
                    <div class="text-entry" style="height: 200px;">
                        <form>
                            <h2 style="color: #fff;">Add a new achievement</h2>
                            <label style="padding-top: 10%;" for="name">Game:</label>
                            <input type="text" id="name" name="name">
                            <label style="padding-top: 10%;" for="achievement">Achievement:</label>
                            <input type="text" id="achievement" name="achievement">

                            <button type="submit">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        <!-- End Page Body -->
        </div>
        <script>
            //TO-DO:
            //Games should be dependent on list gathered from "My List"
            //Should be using games[] to display everything, so then achievements can be appended to list entries
            //Possibly add "Achievements" title at the top with total # of trophies

            // proof of concept, pull from a DB later
            let games = <?php echo json_encode($result->fetch_all(MYSQLI_ASSOC)); ?>;

            //currently this is being hardcoded in the body
            games.forEach((game) => {
                // Add a li, game title to picture-list ul
                let pictureList = document.getElementById('picture-ul');
                let newLi = document.createElement('li');
                let newImg = document.createElement('img');
                let caption = document.createElement('p');

                caption.innerHTML = 'Game ' + (pictureList.children.length + 1);

                newLi.appendChild(caption);
                pictureList.appendChild(newLi);
            });
        </script>
    </body>
</html>
