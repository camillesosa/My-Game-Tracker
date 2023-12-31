<?php
    session_start();

if (isset($_GET['username'])) {
    // Retrieve the value of 'username'
    $username = $_GET['username'];

} else {
    // Handle the case where 'username' is not set
    echo "Username not provided in the URL.";
}

    require_once "util/config.php";
    $mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

	$query = "SELECT user_id, profilePic FROM User WHERE username = '$username';";
    $result = $mysqli->query($query);
if ($result) {
    // Fetch the associative array of the result
    $row = $result->fetch_assoc();

    if ($row) {
        // Retrieve the user_id
        $user_id = $row['user_id'];
        $profilePic = $row['profilePic'];
    } else {
        echo "User not found";
    }

    // Free the result set
    $result->free();
} else {
    // Handle query failure
    echo "Query failed: " . $mysqli->error;
}

    $mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
	$query = "SELECT COUNT(*) FROM user_videogame WHERE user_id = '$user_id';";
    $gamecount_result = $mysqli->query($query);
if ($gamecount_result) {
    // Fetch the result row as an associative array
    $row = $gamecount_result->fetch_assoc();

    // Retrieve the count value
    $gamecount = $row['COUNT(*)'];

    // Free the result set
    $gamecount_result->free();
} else {
    // Handle query failure
    echo "Query failed: " . $mysqli->error;
}


    $mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

    if ($mysqli->connect_errno) {
        printf("Connection Failed: %s\n", $mysqli->connect_error);
        exit();
    }

    $query = "SELECT user_videogame.rating, user_videogame.review, user_videogame.game_id, VideoGame.title, VideoGame.genre, VideoGame.coverArt FROM user_videogame JOIN VideoGame ON user_videogame.game_id = VideoGame.game_id WHERE user_videogame.user_id = '$user_id';";
    $searchResult = null;
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
                <li><a href="mylist.php">My List</a></li>
                <li><a href="achievements.php">Achievements</a></li>
                <li><a href="recommended.php">Recommended</a></li>
                <li><a href="users.php" style="border:2px solid white"><b>Users</b></a></li>
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
						$user = $row['username'];
        					$Pic = $row['profilePic'];
    					} else {
        					echo "Picture not found";
    					}
				}
				echo "<a href='profile.php'>$user </a>";
				echo "<img src='$Pic' style='width: 50px;'>";
				$mysqli->close();
    			}
		?>

	    </div>

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

            <div class="picture-list" style="float: left; width: 80%; min-width: 420px; max-width: 1500px;">
                <ul id="picture-ul"></ul>
            </div>

            <!-- Right Column -->
            <div style="justify-content: center; width: auto; text-align: center;" class="container">
                <div>
                    <h2 style="color: #fff; padding-bottom:10%">User</h2>
		    <hr><br>
                    <div class="text-entry" style="height: 200px;">

			        <?php
				        echo "<img src='$profilePic' style='width: 250px;'>";
				        echo "<p><b>$username</b></p>";
				        echo "</li>";
				        echo "<br>";
				        echo "<p>Games Played:</p>";
				        echo "<p>$gamecount</p>";
			        ?>

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
                // Update rating value in div to send to backend
                document.getElementsByName('rating')[0].value = label.id;
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

<?php
    $searchResult = null;

    if(isset($_POST['search'])){
        $query = $_POST['search'];
        echo "<script>document.getElementsByName('search')[0].value = '$query';</script>";

        // Check if game is in user's list
        $mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
        $checkQuery = "SELECT * FROM user_videogame JOIN VideoGame ON user_videogame.game_id = VideoGame.game_id WHERE user_videogame.user_id = '$user_id' AND VideoGame.title LIKE '%$query%';";
        $checkResult = $mysqli->query($checkQuery);
        if (!$checkResult) {
            printf("Query failed: %s\n", $mysqli->error);
            exit();
        }
        $mysqli->close();

        // If it is, update searchResult with game info
        if($checkResult->num_rows > 0){
            $searchResult = $checkResult;
            // Update Title textbox with game title
            $row = $searchResult->fetch_assoc();
            if($row != null){
                echo "<script>document.getElementsByName('gameTitle')[0].value = '{$row['title']}';</script>";
                echo "<script>document.getElementsByName('gameId')[0].value = '{$row['game_id']}';</script>";
                echo "<script>document.getElementsByName('searchPreview')[0].src = '{$row['coverArt']}';</script>";
                echo "<script>document.getElementsByName('rating')[0].value = '{$row['rating']}';</script>";
                echo "<script>highlightStars({$row['rating']});</script>";
                echo "<script>document.getElementsByName('reviewText')[0].value = '{$row['review']}';</script>";
            }
            // Update Rating field with game rating
            // Update Review field with game review
        }
        // If not, search for game in database
        else{
            $query = "SELECT * FROM VideoGame WHERE title LIKE '%$query%' LIMIT 10;";
            $mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
            $searchResult = $mysqli->query($query);
            if (!$searchResult) {
                printf("Query failed: %s\n", $mysqli->error);
                exit();
            }
            $mysqli->close();

            // If game is found, update Title textbox with game title
            $row = $searchResult->fetch_assoc();
            if($row != null){
                echo "<script>document.getElementsByName('gameTitle')[0].value = '{$row['title']}';</script>";
                echo "<script>document.getElementsByName('searchPreview')[0].src = '{$row['coverArt']}';</script>";
                echo "<script>document.getElementsByName('gameId')[0].value = '{$row['game_id']}';</script>";
            }
        }


    }
?>