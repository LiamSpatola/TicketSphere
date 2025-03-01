<?php
    require("utils/sessionCheck.php");

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Checking if the cart exists
    if (isset($_SESSION["cart"]) && !empty($_SESSION["cart"])) {
        $method = $_SERVER["REQUEST_METHOD"];

        // Checking if the form was submitted
        if ($method == "POST") {
            // Getting the details from the form
            $eventID = $_POST['eventID'];

            // Removing the event from the cart
            unset($_SESSION['cart'][$eventID]);
        }
    }

    // Redirecting to the cart
    header("Location: cart.php");
    exit();
?>