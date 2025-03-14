<?php
    require "settings.php";

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $time = $_SERVER["REQUEST_TIME"]; // Getting the current time

    if (isset($_SESSION["LAST_ACTIVITY"])) {
        $last = $_SESSION["LAST_ACTIVITY"]; // Getting the time of the last activity
        $duration = $time - $last; // Seeing how long it has been since the last activity

        if ($duration > Settings::TIMEOUT_DURATION) {
            // Logging the user out if they have timed out
            header("Location: login.php");
            exit;
        }
    }

    $_SESSION["LAST_ACTIVITY"] = $time; // Updating the time of the last activity

    if (!isset($_SESSION["userID"])) {
        // Checking if the user is logged in and redirecting them if they are not
        header("Location: login.php");
        exit;
    }
?>