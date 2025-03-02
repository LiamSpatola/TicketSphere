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
        $ticketID = $_POST["ticketID"];

        // Validating the ticket
        $query = $conn->prepare("SELECT t.ticketID, t.admissionsLeft FROM tickets AS t WHERE t.ticketID = ?");
        $query->bind_param("i", $ticketID);
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php require "templates/head.php"; ?>
    <title>TicketSphere - Scan Tickets</title>
</head>
<body>
    <?php require "templates/nav.php"; ?>
    <h1 class="text-center pt-5">Scan Tickets</h1>

    <div class="container mt-3 bg-light p-5 border rounded"> 
        <form name="scanTickets" action="" method="POST">
            <?php echo $msg; ?>
            <div class="mb-3 mt-3">
                <label for="username">Ticket ID:</label>
                <input type="text" class="form-control" name="ticketID" required>
            </div>
            <button type="submit" class="btn btn-primary">Validate Ticket</button>
        </form>
    </div>
</body>
</html>