<?php
        // Display a message to confirm the update
    echo "<script>console.log($session)</script>";
    setcookie('PHPSESSID', '', -1, '/');
    header("Location: ../loginlogout.php");
?>