<?php
    require "utils/dbConnect.php");

    $method = $_SERVER["REQUEST_METHOD"];

    $msg = "";
    $msg_visibility = "hidden";

    if ($method == "POST") {
        // Getting the user's details from the form
        $firstName = $_POST["firstName"];
        $lastName = $_POST["lastName"];
        $email = $_POST["email"];
        $username = $_POST["username"];
        $password = $_POST["password"];

        // Hashing the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);


        // Trying to register the user and handling duplicate usernames
        try {
            // Building the database query
            $query = $conn->prepare("INSERT INTO users (username, password, firstName, lastName, email, isAdmin) VALUES (?, ?, ?, ?, ?, 0)");
            $query->bind_param("sssss", $username, $hashedPassword, $firstName, $lastName, $email);

            // Running the query and handling the results
            $query->execute();
            $result = $query->get_result();

            header("Location: login.php");
            exit();
        } catch (mysqli_sql_exception $e) {
            if ($e->getCode() == 1062) {
                // Handling cases where the username already exists
                $msg = "Username already exists. Please choose a different one.";
                $msg_visibility = "visible";
            } else {
                $msg = "An error occurred. Please try again later.";
                $msg_visibility = "visible";
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php require "templates/head.php"; ?>
    <title>TicketSphere - Register</title>
</head>
<body>
    <?php require "templates/nav.php"; ?>

    <div class="container mt-3 bg-light p-5 border rounded">
        <h2 class="text-center">Register</h2>
        
        <form name="register" action="" method="POST">
            <p class="text-danger bg-danger bg-opacity-10 border border-danger rounded p-2" style="visibility: <?php echo $msg_visibility; ?>;"><?php echo $msg; ?></p>
            <div class="mb-3 mt-3">
                <label for="username">First Name:</label>
                <input type="text" class="form-control" name="firstName" required>
            </div>
            <div class="mb-3 mt-3">
                <label for="username">Last Name:</label>
                <input type="text" class="form-control" name="lastName" required>
            </div>
            <div class="mb-3 mt-3">
                <label for="username">Email:</label>
                <input type="email" class="form-control" name="email" required>
            </div>
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