<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Get the current date and time and configure the timezone
    require("utils/settings.php");
    date_default_timezone_set(Settings::TIMEZONE);

    $currentDate = date('Y-m-d H:i:s');

    // Fetching all the events from the database which are currently open
    require("utils/dbConnect.php");
    $query = $conn->prepare("SELECT * FROM events AS e WHERE e.ticketSaleStartDate <= ? AND e.ticketSaleEndDate >= ? ORDER BY DATE ASC");
    $query->bind_param("ss", $currentDate, $currentDate);
    $query->execute();

    $result = $query->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php require("templates/head.php"); ?>
    <?php require("utils/sessionCheck.php"); ?>
    <title>TicketSphere - Store</title>
</head>
<body>
    <?php require("templates/nav.php"); ?>
    <h1 class="text-center pt-5"><strong>Ticket Store</strong></h1>

    <div class="container mt-5">
        <div class="row">
            <?php if ($result->num_rows > 0): ?>
                <!-- If there are events, loop through them and display them -->
                <?php while ($event = $result->fetch_assoc()): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h1 class="card-title"><?php echo($event["name"]); ?></h1>
                                <p class="card-text"><strong>Description:</strong> <?php echo($event["description"]); ?></p>
                                <p class="card-text"><strong>Venue:</strong> <?php echo($event["venue"]); ?></p>
                                <p class="card-text"><strong>Date:</strong> <?php echo($event["date"]); ?></p>
                                <p class="card-text"><strong>Tickets Remaining:</strong> <?php echo($event["numberOfTicketsRemaining"]); ?></p>
                                <p class="card-text"><strong>Admissions Per Ticket:</strong> <?php echo($event["admissionsPerTicket"]); ?></p>
                                <p class="card-text"><strong>Ticket Sales Open:</strong> <?php echo($event["ticketSaleStartDate"]); ?></p>
                                <p class="card-text"><strong>Ticket Sales Close:</strong> <?php echo($event["ticketSaleEndDate"]); ?></p>

                                <!-- The add to cart form -->
                                <form action="addToCart.php" method="POST">
                                    <input type="hidden" name="eventID" value="<?php echo $event['eventID']; ?>">
                                    <label for="quantity" class="mr-2"><strong>Quantity:</strong></label>
                                    <input type="number" name="quantity" min="1" max="<?php echo $event['numberOfTicketsRemaining']; ?>" value="1" required>
                                    <button type="submit" class="btn btn-primary">Add to Cart</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <!-- If there are no events, display a message -->
                <p class="text-center">There are no events currently. Please check back later.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>