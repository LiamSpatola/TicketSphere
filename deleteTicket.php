<?php
    require("utils/sessionCheck.php");
    require("utils/adminCheck.php");
    require("utils/dbConnect.php");

    // Checking if the form was submitted
    $method = $_SERVER["REQUEST_METHOD"];
    if ($method == "POST") {
        // Getting the ticket id from the form
        $ticketID = $_POST["ticketID"];

        // Deleting the ticket from the database
        $query = $conn->prepare("DELETE FROM tickets AS t WHERE t.ticketID = ?");
        $query->bind_param("i", $ticketID);
        $query->execute();
    }

    // Redirecting back to manageTickets.php
    header("Location: manageTickets.php");
    exit;
?>