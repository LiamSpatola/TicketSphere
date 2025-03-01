<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
?>

<nav class="navbar navbar-expand-sm bg-primary">
    <div class="container-fluid">
        <a href="index.php" class="navbar-brand text-light">TicketSphere</a>
        <ul class="navbar-nav">
                <?php
                    if (!isset($_SESSION["userID"])) {
                        echo('<li class="navbar-item"><a href="login.php" class="nav-link text-light">Login</a></li>');
                        echo('<li class="navbar-item"><a href="register.php" class="nav-link text-light">Register</a></li>');
                    } else {
                        echo('<li class="navbar-item"><a href="logout.php" class="nav-link text-light">Logout</a></li>');
                    }
                ?>
        </ul>
    </div>
</nav>