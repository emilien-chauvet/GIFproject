<div class="head__footer">
    <div class="container">
        <footer>
            <a class="logo" href="index.php">GIF Project</a>
            <?php
            if (isset($_SESSION['user_id'])) {
                ?><div>
                    <p><a>Legal Notice</a></p>
                    <p><a href="admin_members.php">Members</a></p>
                    <p><a href="admin_actions.php">Activity log</a></p>
                </div><?php
            } else {
                ?>
                <div>
                    <p><a>Legal Notice</a></p>
                    <p><a href="register.php">Registration</a></p>
                    <p><a href="login.php">Login</a></p>
                </div>
                <?php
            }
            ?>
        </footer>
    </div>
</div>