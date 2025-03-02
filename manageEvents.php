<?php
    require("utils/sessionCheck.php");
    require("utils/adminCheck.php");
    require("utils/dbConnect.php");

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Fetching all the events from the database
    $query = "SELECT * FROM events AS e ORDER BY e.date ASC";
    $result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php require("templates/head.php"); ?>
    <title>TicketSphere - Manage Events</title>
</head>
<body>
    <?php require("templates/nav.php"); ?>
    <h1 class="text-center pt-5"><strong>Manage Events</strong></h1>

    <?php if ($result->num_rows > 0): ?>
        <div class="container mt-3">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Event ID</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Venue</th>
                        <th>Date</th>
                        <th>Ticket Sale Start Date</th>
                        <th>Ticket Sale End Date</th>
                        <th>Number of Tickets Remaining</th>
                        <th>Admissions Per Ticket</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- If there are events, loop through them and display them in the table -->
                    <?php while ($event = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo($event["eventID"]); ?></td>
                            <td><?php echo($event["name"]); ?></td>
                            <td><?php echo($event["description"]); ?></td>
                            <td><?php echo($event["venue"]); ?></td>
                            <td><?php echo($event["date"]); ?></td>
                            <td><?php echo($event["ticketSaleStartDate"]); ?></td>
                            <td><?php echo($event["ticketSaleEndDate"]); ?></td>
                            <td><?php echo($event["numberOfTicketsRemaining"]); ?></td>
                            <td><?php echo($event["admissionsPerTicket"]); ?></td>
                            <td>
                                <form name="deleteEvent" action="deleteEvent.php" method="POST">
                                    <input type="hidden" name="eventID" value="<?php echo $event["eventID"]; ?>">
                                    <button type="submit" class="btn btn-link text-danger p-0">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <!-- If there are no events, display a message -->
        <p class="text-center">There are no events.</p>
    <?php endif; ?>
</body>
</html>
</body>
</html>