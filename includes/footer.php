<div class="head__footer">
    <div class="container">
        <footer>
            <a class="logo" href="index.php">GIF Project</a>
            <?php
            if (isset($_SESSION['user_id'])) {
                ?><div>
                    <p><a>Legal Notice</a></p>
                    <p><a href="dashboard.php">Dashboard</a></p>
                    <p><a href="archive_fixed_gif.php">My GIF</a></p>
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