<?php
    session_start();
    // Check if user is logged in
    if (!isset($_SESSION['username'])) {
        header("Location: login.html");
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
    
    $query = "SELECT user_videogame.rating, user_videogame.review, user_videogame.game_id, VideoGame.title, VideoGame.genre, VideoGame.coverArt FROM user_videogame JOIN VideoGame ON user_videogame.game_id = VideoGame.game_id WHERE user_videogame.user_id = '$user_id';";
    
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
        <title>My List</title>
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
                <li><a href="mylist.php" style="border:2px solid white"><b>My List</b></a></li>
                <li><a href="achievements.html">Achievements</a></li>
                <li><a href="recommended.html">Recommended</a></li>
                <li><a href="users.html">Users</a></li>
            </ul>
            <span class="logo" style="width: 30vw;"></span>
            <ul class="right_nav">
                <li>
                    <form name="filterMyList" method="post">
                        <select name="categories" onchange="filterMyList.submit()">
                            <option value="all">All Categories</option>
                            <?php

                                $mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
                                $genreQuery = "SELECT DISTINCT genre FROM VideoGame JOIN user_videogame ON VideoGame.game_id = user_videogame.game_id WHERE user_videogame.user_id = '$user_id';";
                                $genreResult = $mysqli->query($genreQuery);
                                $elements = $genreResult->fetch_all(MYSQLI_ASSOC);
                                
                                mysqli_close($mysqli);

                                foreach($elements as $element){
                                    echo "<option value=\"{$element['genre']}\">{$element['genre']}</option>";
                                }
                            ?>
                        </select>
                    </form>
                    <?php
                        if(isset($_POST['categories'])){
                            $category = $_POST['categories'];
                            // Set select tag value to the category that was selected
                            echo "<script>document.getElementsByName('categories')[0].value = '$category';</script>";
                            
                            if($category == "all"){
                                $query = "SELECT user_videogame.rating, user_videogame.review, user_videogame.game_id, VideoGame.title, VideoGame.genre, VideoGame.coverArt FROM user_videogame JOIN VideoGame ON user_videogame.game_id = VideoGame.game_id WHERE user_videogame.user_id = '$user_id';";
                            }
                            else{
                                $query = "SELECT user_videogame.rating, user_videogame.review, user_videogame.game_id, VideoGame.title, VideoGame.genre, VideoGame.coverArt FROM user_videogame JOIN VideoGame ON user_videogame.game_id = VideoGame.game_id WHERE user_videogame.user_id = '$user_id' AND VideoGame.genre = '$category';";
                            }
                        }
                        else{
                            $query = "SELECT user_videogame.rating, user_videogame.review, user_videogame.game_id, VideoGame.title, VideoGame.genre, VideoGame.coverArt FROM user_videogame JOIN VideoGame ON user_videogame.game_id = VideoGame.game_id WHERE user_videogame.user_id = '$user_id';";
                        }
                        $mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
                        $result = $mysqli->query($query);
                        if (!$result) {
                            printf("Query failed: %s\n", $mysqli->error);
                            exit();
                        }
                        $mysqli->close();
                    ?>
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
        <!-- End Navigation bar -->
        <!-- Begin Page Body -->

        <div class="main">
            <!-- Left Column -->
            
            <div class="picture-list" style="float: left; width: 80%; min-width: 420px; max-width: 1500px;">
                <ul id="picture-ul"></ul>
            </div>

            <!-- Right Column -->
            <div style="justify-content: center; width: auto; text-align: center;" class="container">
                <div>
                    <div class="search-box" style="height: 200px;">
                        <form>
                            <input type="text" placeholder="Search your list...">
                            <button type="submit">Go</button>
                        </form>
                        <img style="padding-top: 10%; width: 120px; height: 120px;" src="img/soulsilver.bmp" alt="Picture 11"><p>Caption 11</p>
                    </div>
                    <div class="text-entry" style="height: 200px;">
                        <form>
                            <h2 style="color: #fff;">Add a new game</h2>
                            <label style="padding-top: 10%;" for="name">Game:</label>
                            <input type="text" id="name" name="name">
                            <label style="padding-top: 10%;">Rating:</label>

                            <div class="rating">
                                <input type="radio" id="star1" name="rating" value="1">
                                <label id="1" title="1 stars"></label>
                                <input type="radio" id="star2" name="rating" value="2">
                                <label id="2" title="2 stars"></label>
                                <input type="radio" id="star3" name="rating" value="3">
                                <label id="3" title="3 stars"></label>
                                <input type="radio" id="star4" name="rating" value="4">
                                <label id="4" title="4 stars"></label>
                                <input type="radio" id="star5" name="rating" value="5">
                                <label id="5" title="5 star"></label>
                            </div>
                            <input type="email" id="email" name="email">
                            <button type="submit">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        <!-- End Page Body -->
        </div>
        <script>
        // Function to highlight all stars up to the one clicked
        function highlightStars(starValue) {
            let starLabels = document.querySelectorAll('.rating label');
            starLabels.forEach((label) => {
                label.style.backgroundColor = 'transparent';
            });
            for (let i = 0; i < starValue; i++) {
                if (starLabels[i].id <= starValue){

                    starLabels[i].style.backgroundColor = 'yellow';
                }
            }
        }

        const starLabels = document.querySelectorAll('.rating label');
        starLabels.forEach((label) => {
            label.addEventListener('click', () => {
                highlightStars(label.id);
            });
        });

        // proof of concept, pull from a DB later
        let games = <?php echo json_encode($result->fetch_all(MYSQLI_ASSOC)); ?>;

        games.forEach((game) => {
            console.log(game);
            // Add a li, img to picture-list ul
            let pictureList = document.getElementById('picture-ul');
            let newLi = document.createElement('li');
            let newImg = document.createElement('img');
            let caption = document.createElement('p');

            newImg.src = game['coverArt'];
            newImg.alt = game['title'];
            caption.innerHTML = game['review'];

            newLi.appendChild(newImg);
            newLi.appendChild(caption);
            pictureList.appendChild(newLi);

            // Add rating to new li
            let ratingDiv = document.createElement('div');
            ratingDiv.setAttribute('class', 'rating');

            // TODO: Grab rating from DB later
            let rating =  parseInt(game['rating']);

            for (let i = 0; i < 5; i++) {
                let newLabel = document.createElement('staticlabel');
                let oldStyle = newLabel.getAttribute('style');
                newLabel.setAttribute('style', oldStyle + 'cursor: default;');
                newLabel.id = (i + 1);
                newLabel.title = (i + 1) + ' stars';
                if (i < rating) {
                    newLabel.style.backgroundColor = 'yellow';
                }
                ratingDiv.appendChild(newLabel);
            }
            newLi.appendChild(ratingDiv);
        });
        </script>
    </body>
</html>

