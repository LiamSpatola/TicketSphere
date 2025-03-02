<?php
    require "utils/sessionCheck.php";
    require "utils/adminCheck.php";
    require "utils/dbConnect.php";

    $method = $_SERVER["REQUEST_METHOD"];

    $msg = "";
    $msg_visibility = "hidden";

    if ($method == "GET") {

        // Getting the user id
        $userID = $_GET["id"];

        // Handling errors
        if (isset($_GET["err"])) {
            $msg = ($_GET["err"] == 1 ? "Username already exists. Please choose a different one." : "An error occurred. Please try again later.");
            $msg_visibility = "visible";
        }

        // Fetching the user data
        $query = $conn->prepare("SELECT * FROM users AS u WHERE userID = ?");
        $query->bind_param("i", $userID);
        $query->execute();
        $result = $query->get_result();
        $result = $result->fetch_assoc();
    } else {
        // Getting the data from the form
        $userID = $_POST["userID"];
        $firstName = $_POST["firstName"];
        $lastName = $_POST["lastName"];
        $email = $_POST["email"];
        $username = $_POST["username"];
        $password = $_POST["password"];
        $isAdmin = ($_POST["adminStatus"] == "on" ? 1 : 0);

        // Hashing the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Trying to update the user and handling cases where the username is a duplicate
        try {
            // Building the update query
            $query = $conn->prepare("UPDATE users SET firstName = ?, lastName = ?, email = ?, username = ?, password = ?, isAdmin = ? WHERE userID = ?");
            $query->bind_param("sssssii", $firstName, $lastName, $email, $username, $hashedPassword, $isAdmin, $userID);

            // Running the query and handling the results
            $query->execute();

            header("Location: manageUsers.php");
            exit();
        } catch (mysqli_sql_exception $e) {
            if ($e->getCode() == 1062) {
                // Error code 1 shows a duplicate username
                $errorCode = 1;
            } else {
                // Error code 2 shows some other error
                $errorCode = 2;
            }

            // Reloading the page in GET mode to get the form data again
            header("Location: editUser.php?id=$userID&err=$errorCode");
            exit;
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php require "templates/head.php"; ?>
    <title>TicketSphere - Edit User</title>
</head>
<body>
    <?php require "templates/nav.php"; ?>

    <div class="container mt-3 bg-light p-5 border rounded">
        <h2 class="text-center">Edit User</h2>
        
        <form name="editUser" action="" method="POST">
            <p class="text-danger bg-danger bg-opacity-10 border border-danger rounded p-2" style="visibility: <?php echo $msg_visibility; ?>;"><?php echo $msg; ?></p>
            <input type="hidden" name="userID" value="<?php echo $userID); ?>">
            <div class="mb-3 mt-3">
                <label for="username">First Name:</label>
                <input type="text" class="form-control" name="firstName" value="<?php echo $result["firstName"]; ?>" required>
            </div>
            <div class="mb-3 mt-3">
                <label for="username">Last Name:</label>
                <input type="text" class="form-control" name="lastName" value="<?php echo $result["lastName"]; ?>" required>
            </div>
            <div class="mb-3 mt-3">
                <label for="username">Email:</label>
                <input type="email" class="form-control" name="email" value="<?php echo $result["email"]; ?>" required>
            </div>
            <div class="mb-3 mt-3">
                <label for="username">Username:</label>
                <input type="text" class="form-control" name="username" value="<?php echo $result["username"]; ?>" required>
            </div>
            <div class="mb-3">
                <label for="password">Password:</label>
                <input type="password" class="form-control" name="password" required>
            </div>
            <div class="mb-3">
                <label for="adminStatus" class="form-check-label">Admin:</label>
                <input type="checkbox" class="form-check-input" name="adminStatus" <?php echo $result["isAdmin"] == 1 ? "checked" : ""; ?>>
            </div>
            <button type="submit" class="btn btn-primary">Update</button><a href="manageUsers.php" class="btn btn-danger ms-3">Cancel</a>
        </form>
    </div>
</body>
</html>