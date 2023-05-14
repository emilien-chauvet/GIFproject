<?php
session_start();
require_once "includesFixedGif/db_gifs_fixes.php";
include "includes/confirmationBanner.php";
include 'functions.php';

// Récupère le $user_id et permet d'empêcher un utilisateur non connecté d'accéder à la page
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    // L'utilisateur n'est pas connecté, redirection vers page index
    header("Location: index.php");
    exit;
}

$gifs_fixes = getUserFixedGifs($user_id);
$gifCount = count($gifs_fixes);
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Dashboard</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <?php include_once 'includes/header.php' ?>
        <div class="container">
            <h1>My GIF</h1>
            <?php if ($user['status'] === "premium") { ?>
                <a href="create_fixed_gif.php">Create a new GIF</a>
                <br>
            <?php } elseif ($user['status'] === "user" && $gifCount < 1) { ?>
                <a href="create_fixed_gif.php">Create a new GIF</a>
                <br>
            <?php } else { ?>
                <p>You can't have another GIF, to get more <a href="http://localhost/GIFproject/checkout.php" style="text-decoration: underline;">become premium.</a></p>
                <br>
            <?php } ?>
            <?php foreach ($gifs_fixes as $fixed_gif) : ?>
                <a href="single_fixed_gif.php?id=<?php echo $fixed_gif['id'] ?>">
                    <img style="box-shadow:0px 0px 3px black;margin-top: 1rem;" src="http://localhost/gifproject/<?php echo $fixed_gif['fixed_gif'] ?>">
                </a>
                <br>
            <?php endforeach; ?>
        </div>
        <?php include_once 'includes/footer.php' ?>
    </body>
</html>

