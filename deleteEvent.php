<?php
    require("utils/sessionCheck.php");
    require("utils/adminCheck.php");
    require("utils/dbConnect.php");

    // Checking if the form was submitted
    $method = $_SERVER["REQUEST_METHOD"];
    if ($method == "POST") {
        // Getting the event id from the form
        $eventID = $_POST["eventID"];

        // Deleting the event from the database
        $query = $conn->prepare("DELETE FROM events AS e WHERE e.eventID = ?");
        $query->bind_param("i", $eventID);
        $query->execute();
    }

    // Redirecting back to manageEvents.php
    header("Location: manageEvents.php");
    exit;
?>