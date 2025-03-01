<?php
    // Getting the database credentials
    require("utils/settings.php");

    // Initiating a connection to the database
    $conn = new mysqli(Settings::DB_SERVER, Settings::DB_USER, Settings::DB_PASSWORD, Settings::DB_NAME);

    if ($conn->connect_error) {
        // Handling connection errors
        die("Failed to connect to the database: ".$conn->connect_error);
    }
?>