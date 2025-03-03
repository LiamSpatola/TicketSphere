<?php
    require "utils/sessionCheck.php";
    require "utils/dbConnect.php";

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Seeing if a ticket id has been provided
    if (!isset($_GET["id"]) || empty($_GET["id"])) {
        die("Invalid ticket.");
    }

    $ticketID = intval($_GET["id"]);
    $userID = $_SESSION["userID"];

    // Fetching the ticket details, including event and user information
    $query = $conn->prepare("
        SELECT 
            t.ticketID, t.purchaseDate, t.admissionsLeft,
            e.name AS eventName, e.description, e.venue, e.date,
            u.firstName, u.lastName
        FROM tickets AS t
        INNER JOIN events AS e ON t.eventID = e.eventID
        INNER JOIN users AS u ON t.userID = u.userID
        WHERE t.ticketID = ? AND t.userID = ?
    ");

    $query->bind_param("ii", $ticketID, $userID);
    $query->execute();
    $result = $query->get_result();

    // Handling cases where the user does not own the ticket or it doesn't exist
    if ($result->num_rows === 0) {
        die("The ticket was not found or the user is not authorized to access it.");
    }

    $ticket = $result->fetch_assoc();

    $barcodeURL = "https://barcode.tec-it.com/barcode.ashx?data=".urlencode($ticket["ticketID"])."&code=Code128&translate-esc=on";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php require "templates/head.php"; ?>
    <title>TicketSphere - View Ticket</title>
</head>
<body>
    <?php require "templates/nav.php"; ?>
    <h1 class="text-center pt-5"><strong>View Ticket</strong></h1>
    
    <div class="text-center mt-3">
        <h2><strong>Event: </strong><?php echo $ticket["eventName"]; ?></h2>
        <p><strong>Description: </strong><?php echo $ticket["description"]; ?></p>
        <p><strong>Venue: </strong><?php echo $ticket["venue"]; ?></p>
        <p><strong>Event Date: </strong><?php echo $ticket["date"]; ?></p>
        <p><strong>Ticket Holder: </strong><?php echo $ticket["firstName"]." ".$ticket["lastName"]; ?></p>
        <p><strong>Purchase Date: </strong><?php echo $ticket["purchaseDate"]; ?></p>
        <p><strong>Admissions Left: </strong><?php echo $ticket["admissionsLeft"]; ?></p>

        <img src="<?php echo $barcodeURL; ?>" alt="Ticket Barcode" class="mt-3">
        <h4 class="mt-3">Show this ticket at the venue entrance.</h4>
        <a href="myTickets.php" class="mt-3">Return</a>
    </div>
</body>
</html>