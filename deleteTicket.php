<?php
    require("utils/sessionCheck.php");
    require("utils/adminCheck.php");
    require("utils/dbConnect.php");

    // Checking if the form was submitted
    $method = $_SERVER["REQUEST_METHOD"];
    if ($method == "POST") {
        // Getting the ticket and event id from the form
        $ticketID = $_POST["ticketID"];
        $eventID = $_POST["eventID"];

        // Deleting the ticket from the database
        $query = $conn->prepare("DELETE FROM tickets AS t WHERE t.ticketID = ?");
        $query->bind_param("i", $ticketID);
        $query->execute();

        // Increasing the number of tickets remaining
        $query = $conn->prepare("UPDATE events AS e SET e.numberOfTicketsRemaining = e.numberOfTicketsRemaining + 1 WHERE e.eventID = ?");
        $query->bind_param("i", $eventID);
        $query->execute();
    }

    // Redirecting back to manageTickets.php
    header("Location: manageTickets.php");
    exit;
?>