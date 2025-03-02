<?php
    require("utils/dbConnect.php");

    // Initializing session memory and clearing it
    session_start();
    session_destroy();
    session_unset();
    session_start();

    $method = $_SERVER["REQUEST_METHOD"];

    $msg = "";
    $msg_visibility = "hidden";

    if ($method == "POST") {
        // Getting the username and password
        $username = $_POST["username"];
        $password = $_POST["password"];

        // Building the database query and connecting to the database
        $query = $conn->prepare("SELECT u.userID, u.password, u.isAdmin FROM users AS u WHERE u.username = ?");
        $query->bind_param("s", $username);

        // Running the query and handling the results
        $query->execute();
        $query->store_result();


        if ($query->num_rows > 0) {
            // Handling cases where a user exists
            $query->bind_result($userID, $hashedPassword, $isAdmin);
            $query->fetch();

            // Verifying the password
            if (password_verify($password, $hashedPassword)) {
                // Handling cases where the password is correct
                $_SESSION["userID"] = $userID;
                $_SESSION["isAdmin"] = ($isAdmin == 1) ? true : false;

                // Redirecting to the home page
                header("Location: index.php");
                exit();
            } else {
                // Handling cases where the password is incorrect
                $msg = "Incorrect password or username. Please try again.";
                $msg_visibility = "visible";
            }
        } else {
            // Handling cases where no user exists
            $msg = "Incorrect password or username. Please try again.";
            $msg_visibility = "visible";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php require("templates/head.php"); ?>
    <title>TicketSphere - Login</title>
</head>
<body>
    <?php require("templates/nav.php"); ?>

    <div class="container mt-3 bg-light p-5 border rounded">
        <h2 class="text-center">Login</h2>
        
        <form name="login" action="" method="POST">
            <p class="text-danger bg-danger bg-opacity-10 border border-danger rounded p-2" style="visibility: <?php echo($msg_visibility); ?>;"><?php echo($msg); ?></p>
            <div class="mb-3 mt-3">
                <label for="username">Username:</label>
                <input type="text" class="form-control" name="username" required>
            </div>
            <div class="mb-3">
                <label for="password">Password:</label>
                <input type="password" class="form-control" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
    </div>
</body>
</html>