<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Checking if the cart is empty
    if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
        $cartEmpty = true;
    } else {
        $cartEmpty = false;

        // Fetching the events from the database
        require("utils/dbConnect.php");
        $eventIDs = implode(",", array_keys($_SESSION["cart"]));
        $query = "SELECT e.eventID, e.name, e.venue, e.date FROM events AS e WHERE e.eventID IN ($eventIDs)";
        $result = $conn->query($query);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php require("templates/head.php"); ?>
    <?php require("utils/sessionCheck.php"); ?>
    <title>TicketSphere - Cart</title>
</head>
<body>
    <?php require("templates/nav.php"); ?>
    <h1 class="text-center pt-5"><strong>Cart</strong></h1>

    <?php if (!$cartEmpty): ?>
        <div class="container mt-3">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Venue</th>
                        <th>Date</th>
                        <th>Quantity</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- If there are events in the cart, loop through them and display them in the table -->
                    <?php while ($event = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo($event["name"]); ?></td>
                            <td><?php echo($event["venue"]); ?></td>
                            <td><?php echo($event["date"]); ?></td>
                            <td><?php echo($_SESSION["cart"][$event["eventID"]]["quantity"]); ?></td>
                            <td>
                                <form name="removeFromCart" action="removeFromCart.php" method="POST">
                                    <input type="hidden" name="eventID" value="<?php echo $event["eventID"]; ?>">
                                    <button type="submit" class="btn btn-link text-danger p-0">Remove</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <!-- If there are no events in the cart, display a message -->
        <p class="text-center">Your cart is empty. Add some events through the store.</p>
    <?php endif; ?>

    <div class="d-flex justify-content-center">
        <a href="store.php" class="btn btn-primary">Continue Shopping</a>
    </div>
</body>
</html>