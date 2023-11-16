<?php
// Include config file
require_once "config.php";

$usernameInput = $_POST['new-username'];
$passwordInput = $_POST['new-password'];
$emailInput = $_POST['new-email'];

// Define variables and initialize with empty values
$username = $password = $email = "";
$username_err = $password_err = $email_err = "";


        // Prepare a select statement
        $sql = "SELECT user_id FROM User WHERE username = ?";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);

            // Set parameters
            $param_username = trim($_POST["new-username"]);

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);

                if(mysqli_stmt_num_rows($stmt) == 1){
                    $username_err = "This username is already taken.";
                } else{
                    $username = trim($_POST["new-username"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }

        $sql = "SELECT user_id FROM User WHERE email = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_email);

            // Set parameters
            $param_username = trim($_POST["new-email"]);

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);

                if(mysqli_stmt_num_rows($stmt) == 1){
                    $email_err = "This email address is already taken.";
                } else{
                    $email = trim($_POST["new-email"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }


        $password = trim($_POST["new-password"]);

    // Check input errors before inserting in database
    if(empty($username_err) && empty($password_err) && empty($email_err)){

        // Prepare an insert statement
        $sql = "INSERT INTO User (username, password, email) VALUES (?, ?)";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password, $param_email);

            // Set parameters
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            $param_email = $email;

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                header("location: login.php");
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Close connection
    mysqli_close($link);
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
                <li><a href="home.html">Home</a></li>
                <li><a href="mylist.php">My List</a></li>
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
        <div class="container">
            <div class="left-column"></div>
            <div class="middle-column">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <h2>Register</h2>
                    <label for="new-username">Username:</label>
                    <input type="text" id="new-username" name="new-username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>"><br><br>
                    <span class="invalid-feedback"><?php echo $username_err; ?></span>
                    <label for="new-password">Password:</label>
                    <input type="password" id="new-password" name="new-password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>"><br><br>
                    <span class="invalid-feedback"><?php echo $password_err; ?></span>
                    <label for="new-email">Email:</label>
                    <input type="text" id="new-email" name="new-email" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>"><br><br>
                    <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
                    <input type="submit" value="Submit">
                <p>Already have an account? <a href="login.php">Login here</a>.</p>
                </form>
            </div>
            <div class="right-column"></div>
        </div>
    </div>
</body>
</html>