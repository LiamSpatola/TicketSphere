<?php
    // Getting the database credentials
    require("util/settings.php");

    // Initiating a connection to the database
    $conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME);

    if ($conn->connect_error) {
        // Handling connection errors
        die("Failed to connect to the database: ".$conn->connect_error);
    }
?>