<div class="head__banner">
    <div class="container">
        <header>
            <a class="logo" href="index.php">GIF Project</a>
            <?php
            if (isset($_SESSION['user_id'])) {
                ?><div>
                    <p><a href="dashboard.php">Dashboard</a></p>
                    <p><a href="archive_fixed_gif.php">My GIF</a></p>
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