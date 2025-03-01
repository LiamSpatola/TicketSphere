<?php session_start(); ?>

<nav class="navbar navbar-expand-sm bg-primary">
    <div class="container-fluid">
        <a href="index.php" class="navbar-brand text-light">TicketSphere</a>
        <ul class="navbar-nav">
            <li class="navbar-item">
                <?php
                    if (!isset($_SESSION["userID"])) {
                        echo('<a href="login.php" class="nav-link text-light">Login</a>');
                    }
                ?>
            </li>
        </ul>
    </div>
</nav>