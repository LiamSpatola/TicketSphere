<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!$_SESSION["isAdmin"]) {
        header("Location: index.php");
        exit;
    }
?>