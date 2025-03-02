<?php
    require "utils/sessionCheck.php";
    require "utils/adminCheck.php";
    require "utils/dbConnect.php";

    $method = $_SERVER["REQUEST_METHOD"];

    $msg = "";
    $msg_visibility = "hidden";

    if ($method == "POST") {
        // Getting the data from the form
        $name = $_POST["name"];
        $description = $_POST["description"];
        $venue = $_POST["venue"];
        $date = $_POST["date"];
        $ticketSaleStartDate = $_POST["ticketSaleStartDate"];
        $ticketSaleEndDate = $_POST["ticketSaleEndDate"];
        $numberOfTicketsRemaining = $_POST["numberOfTicketsRemaining"];
        $admissionsPerTicket = $_POST["admissionsPerTicket"];

        // Trying to add the event and handling cases where the event name is a duplicate
        try {
            // Building the update query
            $query = $conn->prepare("INSERT INTO events (name, description, venue, date, ticketSaleStartDate, ticketSaleEndDate, numberOfTicketsRemaining, admissionsPerTicket) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $query->bind_param("ssssssii", $name, $description, $venue, $date, $ticketSaleStartDate, $ticketSaleEndDate, $numberOfTicketsRemaining, $admissionsPerTicket);

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
            header("Location: createEvent.php");
            exit;
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php require "templates/head.php"; ?>
    <title>TicketSphere - Create Event</title>
</head>
<body>
    <?php require "templates/nav.php"; ?>

    <div class="container mt-3 bg-light p-5 border rounded">
        <h2 class="text-center">Create Event</h2>
        
        <form name="createEvent" action="" method="POST">
            <p class="text-danger bg-danger bg-opacity-10 border border-danger rounded p-2" style="visibility: <?php echo $msg_visibility; ?>;"><?php echo $msg; ?></p>
            <div class="mb-3 mt-3">
                <label for="name">Name:</label>
                <input type="text" class="form-control" name="name" required>
            </div>
            <div class="mb-3 mt-3">
                <label for="description">Description</label>
                <textarea class="form-control" name="description" rows="3" required></textarea>
            </div>
            <div class="mb-3 mt-3">
                <label for="venue">Venue:</label>
                <input type="text" class="form-control" name="venue" required>
            </div>
            <div class="mb-3 mt-3">
                <label for="date">Date:</label>
                <input type="datetime-local" class="form-control" name="date" required>
            </div>
            <div class="mb-3 mt-3">
                <label for="ticketSaleStartDate">Ticket Sale Start Date:</label>
                <input type="datetime-local" class="form-control" name="ticketSaleStartDate" required>
            </div>
            <div class="mb-3 mt-3">
                <label for="ticketSaleEndDate">Ticket Sale End Date:</label>
                <input type="datetime-local" class="form-control" name="ticketSaleEndDate" required>
            </div>
            <div class="mb-3 mt-3">
                <label for="numberOfTicketsRemaining">Number of Tickets Remaining:</label>
                <input type="number" class="form-control" name="numberOfTicketsRemaining" min="1" required>
            </div>
            <div class="mb-3 mt-3">
                <label for="admissionsPerTicket">Admissions Per Ticket:</label>
                <input type="number" class="form-control" name="admissionsPerTicket" min="1" required>
            </div>
            <button type="submit" class="btn btn-primary">Create</button>
        </form>
    </div>
</body>
</html>