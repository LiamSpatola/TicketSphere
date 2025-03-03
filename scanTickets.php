<?php
    require "utils/sessionCheck.php";
    require "utils/adminCheck.php";
    require "utils/dbConnect.php";

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $msg = "";

    // Checking if the form was submitted
    $method = $_SERVER["REQUEST_METHOD"];
    if ($method == "POST") {

        if (isset($_POST["eventID"])) {
            // Saving the current event to session memory if the user updates it
            $_SESSION["ticketScanningEventID"] = $_POST["eventID"];

            // Getting the current event name and saving it to session memory so it can be shown in the form
            $query = $conn->prepare("SELECT name FROM events WHERE eventID = ?");
            $query->bind_param("i", $_POST["eventID"]);
            $query->execute();
            $result = $query->get_result();
            $result = $result->fetch_assoc();

            $_SESSION["ticketScanningEventName"] = $result["name"];
        } 
        
        if (!isset($_SESSION["ticketScanningEventID"])) {
            // If the user has not selected an event, returning an error
            $msg = "<p class='text-danger bg-danger bg-opacity-10 border border-danger rounded p-2'>No Event Selected!</p>";
        } else {
            $ticketID = $_POST["ticketID"];
            $eventID = $_SESSION["ticketScanningEventID"];

            // Validating the ticket
            $query = $conn->prepare("SELECT t.ticketID, t.admissionsLeft FROM tickets AS t WHERE t.ticketID = ? AND t.eventID = ?");
            $query->bind_param("ii", $ticketID, $eventID);
            $query->execute();
            $result = $query->get_result();
    
            if ($result->num_rows <= 0) {
                // Handling cases where no ticket exists
                $msg = "<p class='text-danger bg-danger bg-opacity-10 border border-danger rounded p-2'>Invalid Ticket ID!</p>";
            } else {
                $ticket = $result->fetch_assoc();
    
                if ($ticket["admissionsLeft"] <= 0) {
                    // Handling cases where there are no admissions left
                    $msg = "<p class='text-danger bg-danger bg-opacity-10 border border-danger rounded p-2'>Ticket Has No Admissions Left!</p>";
                } else {
                    // Deducting one admission
                    $query = $conn->prepare("UPDATE tickets SET admissionsLeft = admissionsLeft - 1 WHERE ticketID = ?");
                    $query->bind_param("i", $ticketID);
                    $query->execute();
    
                    $msg = "<p class='text-success bg-success bg-opacity-10 border border-success rounded p-2'>Ticket Valid!</p>";
                }
            }
        }
    }

    // Getting all the events for the selection dropdown
    $query = "SELECT eventID, name FROM events ORDER BY date DESC";
    $events = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php require "templates/head.php"; ?>
    <title>TicketSphere - Scan Tickets</title>
</head>
<body>
    <?php require "templates/nav.php"; ?>

    <div class="container mt-3 bg-light p-5 border rounded"> 
        <h2 class="text-center">Scan Tickets</h2>
        <form name="scanTickets" action="" method="POST">
            <?php echo $msg; ?>

            <div class="mb-3 mt-3">
                <label for="eventID">Event:</label>
                <select name="eventID" class="form-select" required>
                    <option disabled selected><?php echo (isset($_SESSION["ticketScanningEventName"])) ? $_SESSION["ticketScanningEventName"] : "Select an Event" ?></option>
                    <?php while($event = $events->fetch_assoc()): ?>
                        <option value="<?php echo $event["eventID"]; ?>">
                            <?php echo $event["name"]; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3 mt-3">
                <label for="username">Ticket ID:</label>
                <input type="text" class="form-control" name="ticketID" required>
            </div>
            <button type="submit" class="btn btn-primary">Validate Ticket</button>
        </form>
    </div>
</body>
</html>