<?php
session_start();
require_once 'includesFixedGif/db_gifs_fixes.php';
require_once 'includesFixedGif/form_gifs_fixes.php';
include "includes/confirmationBanner.php";

if (!isset($_GET['id'])) {
    header('Location: archive_fixed_gif.php');
    exit;
}

$gif_id = $_GET['id'];
$gif_data = getFixedGifData($gif_id);
$fixedGifPath = getFixedGifPath($gif_id);
$string_gif = $gif_data['string_gif'];
$order_string = htmlspecialchars($gif_data['gif_order_string']);
$update_frequency = htmlspecialchars($gif_data['gif_update']);

if (isset($_POST['update_gif'])) {
    // Récupérez les données du formulaire
    $fixed_url = $_POST['fixed_url'];
    $redirect_url = $_POST['redirect_url'];
    $gif_font = $_POST['font'];
    $background_color = $_POST['background_color'];

    $gif_checked_source = isset($_POST['include-source']) ? 1 : 0;
    $gif_range_source = $_POST['font-size-source'];
    $gif_color_source = $_POST['color-source'];

    $gif_checked_title = isset($_POST['include-title']) ? 1 : 0;
    $gif_range_title = $_POST['font-size-title'];
    $gif_color_title = $_POST['color-title'];

    $gif_checked_description = isset($_POST['include-description']) ? 1 : 0;
    $gif_range_description = $_POST['font-size-description'];
    $gif_color_description = $_POST['color-description'];

    $gif_checked_category = isset($_POST['include-category']) ? 1 : 0;
    $gif_range_category = $_POST['font-size-category'];
    $gif_color_category = $_POST['color-category'];

    $gif_checked_author = isset($_POST['include-author']) ? 1 : 0;
    $gif_range_author = $_POST['font-size-author'];
    $gif_color_author = $_POST['color-author'];

    $gif_checked_pubDate = isset($_POST['include-pubDate']) ? 1 : 0;
    $gif_range_pubDate = $_POST['font-size-pubDate'];
    $gif_color_pubDate = $_POST['color-pubDate'];

    $order_string = $_POST['order'] ?? "";

    if(isset($_POST['update-daily'])) {
        $update_frequency = 'daily';
    } else if(isset($_POST['update-weekly'])) {
        $update_frequency = 'weekly';
    } else {
        $update_frequency = 'weekly'; // valeur par défaut
    }

    include 'includesFixedGif/gif_generator.php';

    // Mettez à jour les données en base de données
    updateFixedGifData($gif_id, $fixed_url, $fixedGifPath, $redirect_url, $gif_font, $background_color, $gif_checked_source, $gif_range_source, $gif_color_source, $gif_checked_title, $gif_range_title, $gif_color_title, $gif_checked_description, $gif_range_description, $gif_color_description, $gif_checked_category, $gif_range_category, $gif_color_category, $gif_checked_author, $gif_range_author, $gif_color_author, $gif_checked_pubDate, $gif_range_pubDate, $gif_color_pubDate, $order_string, $update_frequency);

    log_activity($user['id'], 'Update GIF', 'The user ' . $user['username'] . ' updated its GIF.');

    // Récupérez à nouveau les données du GIF fixe après la mise à jour
    $gif_data = getFixedGifData($gif_id);
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>GIF : <?php echo $gif_data['fixed_gif'] ?></title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <?php include_once 'includes/header.php' ?>
        <div class="container">
            <h1>GIF : <?php echo $gif_data['fixed_gif'] ?></h1>
            <img style="box-shadow: 0px 0px 3px black;" src="http://localhost/gifproject/<?php echo $gif_data['fixed_gif']; ?>">
            <?php displayForm($gif_data, $user, $update_frequency); ?>
        </div>
    </body>
</html>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="assets/js/newFixedGifs.js" defer></script>