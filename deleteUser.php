<?php
    require("utils/sessionCheck.php");
    require("utils/adminCheck.php");
    require("utils/dbConnect.php");

    // Checking if the form was submitted
    $method = $_SERVER["REQUEST_METHOD"];
    if ($method == "POST") {
        // Getting the user id from the form
        $userID = $_POST["userID"];

        // Deleting the user from the database
        $query = $conn->prepare("DELETE FROM users AS u WHERE u.userID = ?");
        $query->bind_param("i", $userID);
        $query->execute();
    }

    // Redirecting back to manageUsers.php
    header("Location: manageUsers.php");
    exit;
?>