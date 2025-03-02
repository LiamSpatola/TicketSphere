<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
?>

<nav class="navbar navbar-expand-sm bg-primary">
    <div class="container-fluid">
        <a href="index.php" class="navbar-brand text-light">TicketSphere</a>
        <ul class="navbar-nav">
            <?php if (!isset($_SESSION["userID"])): ?>
                <!-- Nav bar for users not logged in -->
                <li class="navbar-item">
                    <a href="login.php" class="nav-link text-light">Login</a>
                </li>
                <li class="navbar-item">
                    <a href="register.php" class="nav-link text-light">Register</a>
                </li>
            <?php elseif (isset($_SESSION["userID"]) && !$_SESSION["isAdmin"]): ?>
                <!-- Nav bar for non-admin logged in users -->
                <li class="navbar-item">
                    <a href="myTickets.php" class="nav-link text-light">My Tickets</a>
                </li>
                <li class="navbar-item">
                    <a href="store.php" class="nav-link text-light">Store</a>
                </li>
                <li class="navbar-item">
                    <a href="cart.php" class="nav-link text-light">Cart</a>
                </li>
                <li class="navbar-item">
                    <a href="logout.php" class="nav-link text-light">Logout</a>
                </li>
            <?php else: ?>
                <!-- Nav bar for admin users -->
                <li class="navbar-item">
                    <a href="scanTickets.php" class="nav-link text-light">Scan Tickets</a>
                </li>
                <li class="navbar-item">
                    <a href="logout.php" class="nav-link text-light">Logout</a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</nav>