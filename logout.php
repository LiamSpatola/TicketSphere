<?php
    // Deleting the session data
    session_start();
    session_destroy();
    session_unset();

    // Redirecting the user home
    header("Location: index.php");
    exit;
?>