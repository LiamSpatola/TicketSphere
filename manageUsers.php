<?php
    require("utils/sessionCheck.php");
    require("utils/adminCheck.php");
    require("utils/dbConnect.php");

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Fetching all the users from the database
    $query = "SELECT * FROM users AS u ORDER BY u.lastName, u.firstName ASC";
    $result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php require("templates/head.php"); ?>
    <title>TicketSphere - Manage Users</title>
</head>
<body>
    <?php require("templates/nav.php"); ?>
    <h1 class="text-center pt-5"><strong>Manage Users</strong></h1>

    <?php if ($result->num_rows > 0): ?>
        <div class="container mt-3">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>User ID</th>
                        <th>Username</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Admin</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- If there are users, loop through them and display them in the table -->
                    <?php while ($user = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo($user["userID"]); ?></td>
                            <td><?php echo($user["username"]); ?></td>
                            <td><?php echo($user["firstName"]); ?></td>
                            <td><?php echo($user["lastName"]); ?></td>
                            <td><?php echo($user["email"]); ?></td>
                            <td><?php
                                if ($user["isAdmin"] == 0) {
                                    echo("No");
                                } else {
                                    echo("Yes");
                                }
                            ?></td>
                            <td>
                                <a href="editUser.php?id=<?php echo $user['userID']; ?>" class="btn text-primary btn-link">Edit</a>
                                <?php if ($user["userID"] != $_SESSION["userID"]): ?>
                                    <form name="deleteUser" action="deleteUser.php" method="POST">
                                        <input type="hidden" name="userID" value="<?php echo $user["userID"]; ?>">
                                        <button type="submit" class="btn btn-link text-danger p-0">Delete</button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <!-- If there are no users, display a message -->
        <p class="text-center">There are no users.</p>
    <?php endif; ?>
</body>
</html>
</body>
</html>