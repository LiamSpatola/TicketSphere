<?php
    require "utils/sessionCheck.php";
    require "utils/adminCheck.php";
    require "utils/dbConnect.php";

    // Fetching all the tickets from the database
    $query = "
        SELECT t.ticketID, t.purchaseDate, t.admissionsLeft, e.eventID, e.name, e.date, e.venue, u.firstName, u.lastName
        FROM tickets AS t
        INNER JOIN events AS e ON t.eventID = e.eventID
        INNER JOIN users AS u ON t.userID = u.userID
        ORDER BY t.purchaseDate ASC, t.admissionsLeft DESC
    ";
    $result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php require "templates/head.php"; ?>
    <title>TicketSphere - Manage Tickets</title>
</head>
<body>
    <?php require "templates/nav.php"; ?>
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
                            <?php if ($ticket["admissionsLeft"] > 0): ?>
                                <td><?php echo $ticket["ticketID"]; ?></td>
                                <td><?php echo $ticket["purchaseDate"]; ?></td>
                                <td><?php echo $ticket["firstName"]." ".$ticket["lastName"]; ?></td>
                                <td><?php echo $ticket["name"]; ?></td>
                                <td><?php echo $ticket["date"]; ?></td>
                                <td><?php echo $ticket["venue"]; ?></td>
                                <td><?php echo $ticket["admissionsLeft"]; ?></td>
                                <td>
                                    <a href="editTicket.php?id=<?php echo $ticket['ticketID']; ?>" class="btn text-primary btn-link">Edit</a>
                                    <form name="deleteTicket" action="deleteTicket.php" method="POST">
                                        <input type="hidden" name="ticketID" value="<?php echo $ticket["ticketID"]; ?>">
                                        <input type="hidden" name="eventID" value="<?php echo $ticket["eventID"]; ?>">
                                        <button type="submit" class="btn btn-link text-danger p-0">Delete</button>
                                    </form>
                                </td>
                            <?php else: ?>
                                <td class="text-muted"><?php echo $ticket["ticketID"]; ?></td>
                                <td class="text-muted"><?php echo $ticket["purchaseDate"]; ?></td>
                                <td class="text-muted"><?php echo $ticket["firstName"]." ".$ticket["lastName"]; ?></td>
                                <td class="text-muted"><?php echo $ticket["name"]; ?></td>
                                <td class="text-muted"><?php echo $ticket["date"]; ?></td>
                                <td class="text-muted"><?php echo $ticket["venue"]; ?></td>
                                <td class="text-muted"><?php echo $ticket["admissionsLeft"]; ?></td>
                                <td>
                                    <a href="editTicket.php?id=<?php echo $ticket['ticketID']; ?>" class="btn text-muted btn-link">Edit</a>
                                    <form name="deleteTicket" action="deleteTicket.php" method="POST">
                                        <input type="hidden" name="ticketID" value="<?php echo $ticket["ticketID"]; ?>">
                                        <input type="hidden" name="eventID" value="<?php echo $ticket["eventID"]; ?>">
                                        <button type="submit" class="btn btn-link text-muted p-0">Delete</button>
                                    </form>
                                </td>
                            <?php endif; ?>
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