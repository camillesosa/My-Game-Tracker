<?php
    session_start();
    // Check if user is logged in
    if (isset($_SESSION['username'])) {
        header("Location: logout.html");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Login Page</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <h1 class="header">My Game Tracker</h1>
        <div class="header">
            <ul class="left_nav">
                <li><a href="home.php">Home</a></li>
                <li><a href="mylist.php">My List</a></li>
                <li><a href="achievements.html">Achievements</a></li>
                <li><a href="recommended.html">Recommended</a></li>
                <li><a href="users.php">Users</a></li>
                <li><a href="admin.php">Admin</a></li>
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
                <li><a href="loginlogout.php" style="border:2px solid white"><b>Login/Logout</b></a></li>
            </ul>
        </div>
        <div class="main">
            <div class="container">
                <div class="left-column"></div>
                <div class="middle-column">
                    <form action="login.php" method="post">
                        <h2>Login</h2>
                        <label for="username">Username:</label>
                        <input type="text" id="username" name="username"><br><br>
                        <label for="password">Password:</label>
                        <input type="password" id="password" name="password"><br><br>
                        <input type="submit" name="login" value="Log In"></input>
						<a href="#" id="forgot-password-link">Forgot Password?</a> <!-- POPUP -->
                    </form>
                </div>
                <div class="right-column"></div>
            </div>
            <div class="form-container">
                <form action="register.php" method="post">
                    <h2>Register</h2>
                    <label for="new-username">Username:</label>
                    <input type="text" id="new-username" name="new-username"><br><br>
                    <label for="new-password">Password:</label>
                    <input type="password" id="new-password" name="new-password"><br><br>
                    <label for="new-email">Email:</label>
                    <input type="text" id="new-email" name="new-email"><br><br>
                    <input type="submit" name="register" value="Register"></input>
                </form>
            </div>
			<div class="popup" id="forgot-password-popup">
				<form>
					<h2>Forgot Password</h2>
					<p>Enter the email you used to register:</p>
					<input type="email" id="forgot-email" name="forgot-email" placeholder="Email">
					<button type="submit" id="forgot-password-submit">Submit</button>
				</form>
			</div>
        </div>
		<script src="popup.js"></script>
    </body>
</html>
