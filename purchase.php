<?php
    require("utils/sessionCheck.php");

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Checking if the cart exists
    if (isset($_SESSION["cart"]) && !empty($_SESSION["cart"])) {
        // Processing the items in the cart
        $eventIDs = array_keys($_SESSION["cart"]);

        // Getting the user id
        $userID = $_SESSION["userID"];

        // Beginning a transaction and connecting to the database
        require("utils/dbConnect.php");
        $conn->begin_transaction();

        // Adding the tickets to the database
        try {
            foreach ($eventIDs as $eventID) {
                $quantity = $_SESSION["cart"][$eventID]["quantity"];

                for ($i = 0; $i < $quantity; $i++) {
                    // Finding how many ticket admissions are left
                    $query = "SELECT e.admissionsPerTicket FROM events AS e WHERE e.eventID = $eventID";
                    $result = $conn->query($query);
                    $admissionsLeft = $result->fetch_assoc()["admissionsPerTicket"];

                    $purchaseDate = date('Y-m-d H:i:s');

                    // Building the insert query
                    $query = $conn->prepare("INSERT INTO tickets (eventID, userID, purchaseDate, admissionsLeft) VALUES (?, ?, ?, ?)");
                    $query->bind_param("iisi", $eventID, $userID, $purchaseDate, $admissionsLeft);

                    // Running the query
                    $query->execute();

                    // Updating the number of tickets left
                    $query = $conn->prepare("UPDATE events AS e SET e.numberOfTicketsRemaining = e.numberOfTicketsRemaining - 1 WHERE e.eventID = ?");
                    $query->bind_param("i", $eventID);
                    $query->execute();
                }
            }
            $conn->commit();
        } catch (Exception $e) {
            $conn->rollback();
            die("An error occurred: ".$e);
        }

        // Unsetting the cart
        unset($_SESSION["cart"]);

        header("Location: success.php");
        exit;
    } else {
        header("Location: cart.php");
        exit;
    }
?>