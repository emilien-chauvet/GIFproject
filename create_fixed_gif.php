<?php
session_start();
require_once 'includesFixedGif/db_gifs_fixes.php';
require_once 'includesFixedGif/form_gifs_fixes.php';
include "includes/confirmationBanner.php";

// Récupère le $user_id et permet d'empêcher un utilisateur non connecté d'accéder à la page
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    // L'utilisateur n'est pas connecté, redirection vers page index
    header("Location: index.php");
    exit;
}

// Vérifie si le bouton "Créer le GIF fixe" a été soumis
if (isset($_POST['create_gif'])) {
    // Récupérez les données du formulaire
    $fixed_url = $_POST['fixed_url'];
    //$redirect_url = $_POST['redirect_url'];
    $redirect_url = $_POST['redirect_url'] ?? "";
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

    $string_gif = bin2hex(random_bytes(12));

    if(isset($_POST['update-daily'])) {
        $update_frequency = 'daily';
    } else if(isset($_POST['update-weekly'])) {
        $update_frequency = 'weekly';
    } else {
        $update_frequency = 'weekly'; // valeur par défaut
    }

    // C'est ici qu'on appelle gif_generator.php --> Cela permet de générer le GIF en utilisant les informations du formulaire et d'insérer le chemin du GIF généré dans la base de données
    include 'includesFixedGif/gif_generator.php';

    // Créez un nouveau GIF fixe dans la base de données
    createFixedGif($user_id, $fixed_url, $fixedGifPath, $redirect_url, $gif_font, $background_color, $gif_checked_source, $gif_range_source, $gif_color_source, $gif_checked_title, $gif_range_title, $gif_color_title, $gif_checked_description, $gif_range_description, $gif_color_description, $gif_checked_category, $gif_range_category, $gif_color_category, $gif_checked_author, $gif_range_author, $gif_color_author, $gif_checked_pubDate, $gif_range_pubDate, $gif_color_pubDate, $order_string, $string_gif, $update_frequency);

    log_activity($user['id'], 'GIF generated', 'The user ' . $user['username'] . ' generated a new fixed GIF from the RSS feed.');

    // Redirigez l'utilisateur vers la page "Mes GIFs fixes"
    header("Location: archive_fixed_gif.php");
    exit;
}
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
            <h1>Create a GIF</h1>
            <form method="POST" action="create_fixed_gif.php">
                <?php displayForm([], 'create'); ?>
            </form>
        </div>
    </body>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/newFixedGifs.js" defer></script>
</html>