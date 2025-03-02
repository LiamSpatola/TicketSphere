<?php
    require "utils/sessionCheck.php";

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Checking if the cart exists and creating it if it doesn't
    if (!isset($_SESSION["cart"])) {
        $_SESSION["cart"] = [];
    }

    $method = $_SERVER["REQUEST_METHOD"];

    // Checking if the form was submitted
    if ($method == "POST") {
        // Getting the details from the form
        $eventID = $_POST['eventID'];
        $quantity = $_POST['quantity'];

        if (isset($_SESSION["cart"][$eventID])) {
            // Increasing the quantity if already in the cart
            $_SESSION["cart"][$eventID]["quantity"] += $quantity;
        } else {
            // Adding the event to the cart
            $_SESSION["cart"][$eventID] = [
                "eventID" => $eventID,
                "quantity" => $quantity
            ];
        }
    }

    // Redirecting back to the store
    header("Location: store.php");
    exit;
?>