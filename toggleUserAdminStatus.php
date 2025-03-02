<?php
    require("utils/sessionCheck.php");
    require("utils/adminCheck.php");
    require("utils/dbConnect.php");

    // Checking if the form was submitted
    $method = $_SERVER["REQUEST_METHOD"];
    if ($method == "POST") {
        // Getting the user id from the form
        $userID = $_POST["userID"];

        // Getting the current admin status
        $query = $conn->prepare("SELECT u.isAdmin FROM users AS u WHERE u.userID = ?");
        $query->bind_param("i", $userID);
        $query->execute();
        $result = $query->get_result();
        $result = $result->fetch_assoc();

        if ($result["isAdmin"] == 0) {
            $newAdminStatus = 1;
        } else {
            $newAdminStatus = 0;
        }

        // Updating the user's admin status
        $query = $conn->prepare("UPDATE users AS u SET isAdmin = ? WHERE u.userID = ?");
        $query->bind_param("ii", $newAdminStatus, $userID);
        $query->execute();
    }

    // Redirecting back to manageUsers.php
    header("Location: manageUsers.php");
    exit;
?>