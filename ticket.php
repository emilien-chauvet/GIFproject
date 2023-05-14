<?php
session_start();
include "db.php";
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
include "includes/confirmationBanner.php";
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Submit a ticket</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <?php include_once 'includes/header.php' ?>
        <div class="container">
            <form action="submit_ticket.php" method="POST">
                <label for="subject">Subject :</label>
                <input type="text" id="subject" name="subject" required>

                <label for="message">Message :</label>
                <textarea id="message" name="message" required></textarea>

                <button type="submit">Submit ticket</button>
            </form>
        </div>
        <?php include_once 'includes/footer.php' ?>
    </body>
</html>