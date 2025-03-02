<?php
    require("utils/sessionCheck.php");
    require("utils/adminCheck.php");
    require("utils/dbConnect.php");

    // Fetching all the tickets from the database
    $query = "
        SELECT t.ticketID, t.purchaseDate, t.admissionsLeft, e.name, e.date, e.venue, u.firstName, u.lastName
        FROM tickets AS t
        INNER JOIN events AS e ON t.eventID = e.eventID
        INNER JOIN users AS u ON t.userID = u.userID
        ORDER BY t.purchaseDate ASC
    ";
    $result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php require("templates/head.php"); ?>
    <title>TicketSphere - Manage Tickets</title>
</head>
<body>
    <?php require("templates/nav.php"); ?>
    <h1 class="text-center pt-5"><strong>Manage Tickets</strong></h1>

    <?php if ($result->num_rows > 0): ?>
        <div class="container mt-3">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Ticket ID</th>
                        <th>Purchase Date</th>
                        <th>Ticket Holder</th>
                        <th>Event Name</th>
                        <th>Event Date</th>
                        <th>Event Venue</th>
                        <th>Admissions Remaining</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- If there are tickets, loop through them and display them in the table -->
                    <?php while ($ticket = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo($ticket["ticketID"]); ?></td>
                            <td><?php echo($ticket["purchaseDate"]); ?></td>
                            <td><?php echo($ticket["firstName"]." ".$ticket["lastName"]); ?></td>
                            <td><?php echo($ticket["name"]); ?></td>
                            <td><?php echo($ticket["date"]); ?></td>
                            <td><?php echo($ticket["venue"]); ?></td>
                            <td><?php echo($ticket["admissionsLeft"]); ?></td>
                            <td>
                                <form name="deleteTicket" action="deleteTicket.php" method="POST">
                                    <input type="hidden" name="ticketID" value="<?php echo $ticket["ticketID"]; ?>">
                                    <button type="submit" class="btn btn-link text-danger p-0">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <!-- If there are no tickets, display a message -->
        <p class="text-center">There are no tickets.</p>
    <?php endif; ?>
</body>
</html>
</body>
</html>