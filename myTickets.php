<?php
    require("utils/sessionCheck.php");
    require("utils/dbConnect.php");

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $userID = $_SESSION["userID"];

    // Fetch tickets for the logged-in user
    $query = $conn->prepare("
        SELECT t.ticketID, t.admissionsLeft, e.name, e.venue, e.date 
        FROM tickets AS t
        INNER JOIN events AS e ON t.eventID = e.eventID
        WHERE t.userID = ?
        ORDER BY e.date ASC, t.admissionsLeft DESC");

    $query->bind_param("i", $userID);
    $query->execute();
    $result = $query->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php require("templates/head.php"); ?>
    <title>TicketSphere - My Tickets</title>
</head>
<body>
    <?php require("templates/nav.php"); ?>
    <div class="container mt-5">
        <h1 class="text-center"><strong>My Tickets</strong></h1>

        <?php if ($result->num_rows > 0): ?>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Event</th>
                            <th>Venue</th>
                            <th>Date</th>
                            <th>Admissions Left</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($ticket = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo($ticket["name"]); ?></td>
                                <td><?php echo($ticket["venue"]); ?></td>
                                <td><?php echo($ticket["date"]); ?></td>
                                <td><?php echo($ticket["admissionsLeft"]); ?></td>
                                <td>
                                    <a href="ticket.php?id=<?php echo $ticket['ticketID']; ?>" class="btn text-primary btn-link">View Ticket</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="text-center">You have no tickets yet.</p>
        <?php endif; ?>
    </div>
</body>
</html>
