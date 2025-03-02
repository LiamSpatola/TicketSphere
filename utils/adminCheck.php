<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Checking if the user is an admin and redirecting them if they are not
    if (!$_SESSION["isAdmin"]) {
        header("Location: index.php");
        exit;
    }
?>