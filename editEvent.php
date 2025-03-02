<?php
    require("utils/sessionCheck.php");
    require("utils/adminCheck.php");
    require("utils/dbConnect.php");

    $method = $_SERVER["REQUEST_METHOD"];

    $msg = "";
    $msg_visibility = "hidden";

    if ($method == "GET") {
        // Getting the event id
        $eventID = $_GET["id"];

        // Handling errors
        if (isset($_GET["err"])) {
            $msg = ($_GET["err"] == 1 ? "Event name already exists. Please choose a different one." : "An error occurred. Please try again later.");
            $msg_visibility = "visible";
        }

        // Fetching the event data
        $query = $conn->prepare("SELECT * FROM events AS e WHERE eventID = ?");
        $query->bind_param("i", $eventID);
        $query->execute();
        $result = $query->get_result();
        $result = $result->fetch_assoc();
    } else {
        // Getting the data from the form
        $eventID = $_POST["eventID"];
        $name = $_POST["name"];
        $description = $_POST["description"];
        $venue = $_POST["venue"];
        $date = $_POST["date"];
        $ticketSaleStartDate = $_POST["ticketSaleStartDate"];
        $ticketSaleEndDate = $_POST["ticketSaleEndDate"];
        $numberOfTicketsRemaining = $_POST["numberOfTicketsRemaining"];
        $admissionsPerTicket = $_POST["admissionsPerTicket"];

        // Trying to update the event and handling cases where the event name is a duplicate
        try {
            // Building the update query
            $query = $conn->prepare("UPDATE events SET name = ?, description = ?, venue = ?, date = ?, ticketSaleStartDate = ?, ticketSaleEndDate = ?, numberOfTicketsRemaining = ?, admissionsPerTicket = ? WHERE eventID = ?");
            $query->bind_param("ssssssiii", $name, $description, $venue, $date, $ticketSaleStartDate, $ticketSaleEndDate, $numberOfTicketsRemaining, $admissionsPerTicket, $eventID);

            // Running the query and handling the results
            $query->execute();

            header("Location: manageEvents.php");
            exit();
        } catch (mysqli_sql_exception $e) {
            if ($e->getCode() == 1062) {
                // Error code 1 shows a duplicate event name
                $errorCode = 1;
            } else {
                // Error code 2 shows some other error
                $errorCode = 2;
            }

            // Reloading the page in GET mode to get the form data again
            header("Location: editEvent.php?id=$eventID&err=$errorCode");
            exit;
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php require("templates/head.php"); ?>
    <title>TicketSphere - Edit Event</title>
</head>
<body>
    <?php require("templates/nav.php"); ?>

    <div class="container mt-3 bg-light p-5 border rounded">
        <h2 class="text-center">Edit Event</h2>
        
        <form name="editUser" action="" method="POST">
            <p class="text-danger bg-danger bg-opacity-10 border border-danger rounded p-2" style="visibility: <?php echo($msg_visibility); ?>;"><?php echo($msg); ?></p>
            <input type="hidden" name="eventID" value="<?php echo($eventID); ?>">
            <div class="mb-3 mt-3">
                <label for="name">Name:</label>
                <input type="text" class="form-control" name="name" value="<?php echo($result["name"]); ?>" required>
            </div>
            <div class="mb-3 mt-3">
                <label for="description">Description</label>
                <textarea class="form-control" name="description" rows="3" required><?php echo($result["description"]); ?></textarea>
            </div>
            <div class="mb-3 mt-3">
                <label for="venue">Venue:</label>
                <input type="text" class="form-control" name="venue" value="<?php echo($result["venue"]); ?>" required>
            </div>
            <div class="mb-3 mt-3">
                <label for="date">Date:</label>
                <input type="datetime-local" class="form-control" name="date" value="<?php echo date('Y-m-d\TH:i', strtotime($result['date'])); ?>" required>
            </div>
            <div class="mb-3 mt-3">
                <label for="ticketSaleStartDate">Ticket Sale Start Date:</label>
                <input type="datetime-local" class="form-control" name="ticketSaleStartDate" value="<?php echo date('Y-m-d\TH:i', strtotime($result['ticketSaleStartDate'])); ?>" required>
            </div>
            <div class="mb-3 mt-3">
                <label for="ticketSaleEndDate">Ticket Sale End Date:</label>
                <input type="datetime-local" class="form-control" name="ticketSaleEndDate" value="<?php echo date('Y-m-d\TH:i', strtotime($result['ticketSaleEndDate'])); ?>" required>
            </div>
            <div class="mb-3 mt-3">
                <label for="numberOfTicketsRemaining">Number of Tickets Remaining:</label>
                <input type="number" class="form-control" name="numberOfTicketsRemaining" value="<?php echo($result["numberOfTicketsRemaining"]); ?>" min="1" required>
            </div>
            <div class="mb-3 mt-3">
                <label for="admissionsPerTicket">Admissions Per Ticket:</label>
                <input type="number" class="form-control" name="admissionsPerTicket" value="<?php echo($result["admissionsPerTicket"]); ?>" min="1" required>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
</body>
</html>