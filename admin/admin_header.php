<div class="head__banner">
    <div class="container">
        <header>
            <a class="logo" href="admin_dashboard.php">GIF Project</a>
            <?php
            if (isset($_SESSION['user_id'])) {
                ?><div>
                    <p><a href="admin_dashboard.php">Dashboard</a></p>
                    <p><a href="admin_tickets.php">Tickets</a></p>
                    <p><a href="admin_members.php">Members</a></p>
                    <p><a href="admin_actions.php">Activity log</a></p>
                </div><?php
            } else {
                ?>
                <div>
                    <p><a href="register.php">Registration</a></p>
                    <p><a href="login.php">Login</a></p>
                </div>
                <?php
            }
            ?>
        </header>
    </div>
</div>