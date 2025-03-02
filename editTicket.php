<?php
    require("utils/sessionCheck.php");
    require("utils/adminCheck.php");
    require("utils/dbConnect.php");

    $method = $_SERVER["REQUEST_METHOD"];

    $msg = "";
    $msg_visibility = "hidden";

    if ($method == "GET") {
        // Handling errors
        if (isset($_GET["err"])) {
            $msg = "An error occurred. Please try again later.";
            $msg_visibility = "visible";
        }

        // Getting the ticket id
        $ticketID = $_GET["id"];

        // Fetching the ticket data
        $query = $conn->prepare("SELECT t.admissionsLeft FROM tickets AS t WHERE ticketID = ?");
        $query->bind_param("i", $ticketID);
        $query->execute();
        $result = $query->get_result();
        $result = $result->fetch_assoc();
    } else {
        // Getting the data from the form
        $ticketID = $_POST["ticketID"];
        $admissionsLeft = $_POST["admissionsLeft"];

        // Trying to update the ticket
        try {
            // Building the update query
            $query = $conn->prepare("UPDATE tickets SET admissionsLeft = ? WHERE ticketID = ?");
            $query->bind_param("ii", $admissionsLeft, $ticketID);

            // Running the query and handling the results
            $query->execute();

            header("Location: manageTickets.php");
            exit();
        } catch (mysqli_sql_exception $e) {
            // Error code 2 shows an SQL error (apart from a duplicate error)
            $errorCode = 2;

            // Reloading the page in GET mode to get the form data again
            header("Location: editTicket.php?id=$ticketID&err=$errorCode");
            exit;
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php require("templates/head.php"); ?>
    <title>TicketSphere - Edit Ticket</title>
</head>
<body>
    <?php require("templates/nav.php"); ?>

    <div class="container mt-3 bg-light p-5 border rounded">
        <h2 class="text-center">Edit Ticket</h2>
        
        <form name="editUser" action="" method="POST">
            <p class="text-danger bg-danger bg-opacity-10 border border-danger rounded p-2" style="visibility: <?php echo($msg_visibility); ?>;"><?php echo($msg); ?></p>
            <input type="hidden" name="ticketID" value="<?php echo($ticketID); ?>">
            <div class="mb-3 mt-3">
                <label for="admissionsLeft">Admissions Remaining:</label>
                <input type="number" class="form-control" name="admissionsLeft" value="<?php echo($result["admissionsLeft"]); ?>" min="0" required>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
</body>
</html>