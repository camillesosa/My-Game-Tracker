<?php
require_once "util/config.php";
?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Users</title>
        <link rel="stylesheet" href="style.css">
        <style>
            .picture-list {
                overflow-y: scroll;
                max-height: 625px;
                border: 5px solid white;
            }
            .picture-list li {
                display: inline-block;
                padding: 15px;
                margin-right: 75px;
                margin-left: 100px;
                text-align: center;
                vertical-align: top;
                width: 400px;
            }
            .picture-list li p {
                margin-top: 1%;
                color: white;
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
            <!-- Populate webpage body here -->
			 <h1 style="text-align: center;">Find A User</h1>
            <hr>
            <br>
                <form action="util/searchUser.php" method="post">
                    <span>
                        <input type="text" id="findUser" name="findUser" placeholder="Search username...">
                        <button type="submit">Go</button>
                    </span>
                </form>
            <br>

            <h2>Users</h2>
            <div class="right_nav">
		<form name="orderUsers" method="post">
                	<select class="right_nav" name="Sort" onchange="orderUsers.submit()">
				<option value="null">Sort By...</option>
		    		<option value="date">Sort By: Date Joined</option>
                    		<option value="A-Z">Sort By: Alphabetical</option>
				<option value="Z-A">Sort By: Rev. Alphabetical</option>
                	</select>
		</form>
		<?php
			session_start();
			if(isset($_POST['Sort'])) {
				$sort = $_POST['Sort'];
				if($sort == "A-Z"){
					//sort by name
					$sql = "SELECT user_id, username, profilePic FROM User ORDER BY username;";
				} else if ($sort == "Z-A"){
					//sort by name reverse
					$sql = "SELECT user_id, username, profilePic FROM User ORDER BY username DESC;";
				} else {
					//sort by date joined
					$sql = "SELECT user_id, username, profilePic FROM User;";
				}
			} else {
				//sort by date joined
				$sql = "SELECT user_id, username, profilePic FROM User;";
			}

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
          						$stmt->bind_result($user_id, $username, $profilePic);
            						$users = array();
            						while($stmt->fetch()){
                						$users[] = array("user_id" => $user_id, "username" => $username, "profilePic" => $profilePic);

                						$users[count($users)-1]["user_id"] = $user_id;
                						$users[count($users)-1]["username"] = $username;
                						$users[count($users)-1]["profilePic"] = $profilePic;

           						}
        					} else{
            						// echo "No games found.";
        					}
    					} else{
        					// echo "Oops! Something went wrong. Please try again later.";
    					}
    					// Close statement
    					$stmt->close();
				} ?>
            </div>
            <div class="picture-list" style="border: 5px solid #202225">
                <ul id="picture-ul">
		  <?php
                    foreach($users as $user){
                    	echo "<li>";
			echo "<img src='$user[profilePic]'>";
			echo "<p><b>$user[username]</b></p>";
			echo "<button onclick=\"window.location.href='selectedUserList.php?username=$user[username]'\">User's List</button>";

			echo "</li>";
                    }
		?>
		</ul>
            </div>
        </div>
    </body>
</html>


