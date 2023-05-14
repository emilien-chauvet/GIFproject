<?php
require_once 'includes/GifCreator.php';
include_once 'functions.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Récupère le $user_id et permet d'empêcher un utilisateur non connecté d'accéder à la page
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    // L'utilisateur n'est pas connecté, redirection vers page index
    header("Location: index.php");
    exit;
}

// Supprime les images déjà existantes s'il y en a
for ($i = 0; $i < 5; $i++) {
    $filename = "fixedGifImages/image" . $i . '_' . $user['username'] . $string_gif . ".png";
    if (file_exists($filename)) {
        unlink($filename);
    }
}
// Supprimer le GIF précédent s'il y en a un
$gifFilename = "fixedGif/gif_" . $user['username'] . $string_gif . ".gif";
if (file_exists($gifFilename)) {
    unlink($gifFilename);
}

$rss = "";
if (!empty($fixed_url)) {
    // Charger le flux RSS à partir du lien
    $rss = simplexml_load_file($fixed_url);
}

$font = "fonts/" . $gif_font;

$max_width = 0;
$total_height = 30; // hauteur de base pour les marges
$base_margin = 5; // marge de base entre les éléments
$text_height = 30; // Hauteur du texte pour la taille de la police // CE PARAMETRE A CHANGER POUR MONTER LA TAILLE DE LA HAUTEUR
$y = 0;
$initial_y = ($total_height - $y) / 2;

if ($gif_checked_source) {
    $total_height += $text_height + $base_margin;
}
if ($gif_checked_title) {
    $total_height += $text_height + $base_margin;
}
if ($gif_checked_description) {
    $total_height += $text_height + $base_margin;
}
if ($gif_checked_category) {
    $total_height += $text_height + $base_margin;
}
if ($gif_checked_author) {
    $total_height += $text_height + $base_margin;
}
if ($gif_checked_pubDate) {
    $total_height += $text_height + $base_margin;
}

if ($rss) {
    // Trouver la largeur maximale parmi les titres et ainsi définir la width des gifs en fonction
    foreach ($rss->channel->item as $item) {
        $title = $item->title;
        $bbox = imagettfbbox($gif_range_title, 0, $font, $title);
        $width = $bbox[2] - $bbox[0];
        if ($width > $max_width) {
            $max_width = $width;
        }
    }

    // Affiche uniquement les 5 derniers éléments et génère une image pour chacun
    for ($i = 0; $i < 5; $i++) {
        $item = $rss->channel->item[$i];
        $source = $item->source;
        $title = $item->title;
        $description = $item->description;
        $description = extract_text_from_description($description);
        //$CondensedDescription = substr($description, 0, 110) . "...";
        $category = $item->category;
        $author = $item->author;
        $pubDate = $item->pubDate;

        // Convertir la date au format DateTime
        //$dateObj = DateTime::createFromFormat(DateTime::RFC2822, $pubDate);

        // Régler le fuseau horaire si nécessaire (par exemple, pour convertir de GMT à l'heure locale)
        //$dateObj->setTimezone(new DateTimeZone('Europe/Paris'));

        // Formater la date en français
        //$formatter = new IntlDateFormatter('fr_FR', IntlDateFormatter::LONG, IntlDateFormatter::SHORT, 'Europe/Paris', IntlDateFormatter::GREGORIAN, 'd MMMM Y à H:mm');
        //$formattedDate = 'Le ' . $formatter->format($dateObj);

        $rgb = hexToRGB($background_color);

        $image = imagecreate(750, $total_height);

        $max_width_title = isset($_POST['maxWidthTitle']) ? intval($_POST['maxWidthTitle']) : 700;
        $max_width_description = isset($_POST['maxWidthDescription']) ? intval($_POST['maxWidthDescription']) : 700;

        // Utilisez les couleurs récupérées du formulaire
        $couleur_fond = imagecolorallocate($image, $rgb[0], $rgb[1], $rgb[2]);

        $couleur_source = sscanf($gif_color_source, "#%02x%02x%02x");
        $couleur_source = imagecolorallocate($image, $couleur_source[0], $couleur_source[1], $couleur_source[2]);

        $couleur_titre = sscanf($gif_color_title, "#%02x%02x%02x");
        $couleur_titre = imagecolorallocate($image, $couleur_titre[0], $couleur_titre[1], $couleur_titre[2]);

        $couleur_description = sscanf($gif_color_description, "#%02x%02x%02x");
        $couleur_description = imagecolorallocate($image, $couleur_description[0], $couleur_description[1], $couleur_description[2]);

        $couleur_categorie = sscanf($gif_color_category, "#%02x%02x%02x");
        $couleur_categorie = imagecolorallocate($image, $couleur_categorie[0], $couleur_categorie[1], $couleur_categorie[2]);

        $couleur_auteur = sscanf($gif_color_author, "#%02x%02x%02x");
        $couleur_auteur = imagecolorallocate($image, $couleur_auteur[0], $couleur_auteur[1], $couleur_auteur[2]);

        $couleur_date = sscanf($gif_color_pubDate, "#%02x%02x%02x");
        $couleur_date = imagecolorallocate($image, $couleur_date[0], $couleur_date[1], $couleur_date[2]);

        $y = $initial_y;

        $order_array = explode(',', $order_string);

        foreach ($order_array as $element_id) {
            switch ($element_id) {
                case 'include-source':
                    if ($gif_checked_source) {
                        imagettftext($image, $gif_range_source, 0, 20, $y + $text_height, $couleur_source, $font, $source);
                        $y += $text_height + $base_margin;
                    }
                    break;
                case 'include-title':
                    if ($gif_checked_title) {
                        $max_chars_title = get_max_chars($font, $gif_range_title, $max_width_title);
                        //$wrappedTitle = wordwrap($title, $max_chars_title, "\n", true);
                        $wrappedTitle = wrap_text_pixels($font, $gif_range_title, $title, $max_width_title);
                        $wrappedTitle_lines = explode("\n", $wrappedTitle);
                        $title_line = $wrappedTitle_lines[0]; // Utilisez uniquement la première ligne
                        $title_line = $title_line . "...";
                        imagettftext($image, $gif_range_title, 0, 20, $y + $text_height, $couleur_titre, $font, $title_line);
                        $y += $text_height + $base_margin;
                    }
                    break;
                case 'include-description':
                    if ($gif_checked_description) {
                        $max_chars_description = get_max_chars($font, $gif_range_description, $max_width_description);
                        //$wrappedDescription = wordwrap($description, $max_chars_description, "\n", true);
                        $wrappedDescription = wrap_text_pixels($font, $gif_range_description, $description, $max_width_description);
                        $wrappedDescription_lines = explode("\n", $wrappedDescription);
                        $description_line = $wrappedDescription_lines[0]; // Utilisez uniquement la première ligne
                        $description_line = $description_line . "...";
                        imagettftext($image, $gif_range_description, 0, 20, $y + $text_height, $couleur_description, $font, $description_line);
                        $y += $text_height + $base_margin;
                    }
                    break;
                case 'include-category':
                    if ($gif_checked_category) {
                        imagettftext($image, $gif_range_category, 0, 20, $y + $text_height, $couleur_categorie, $font, $category);
                        $y += $text_height + $base_margin;
                    }
                    break;
                case 'include-author':
                    if ($gif_checked_author) {
                        imagettftext($image, $gif_range_author, 0, 20, $y + $text_height, $couleur_auteur, $font, $author);
                        $y += $text_height + $base_margin;
                    }
                    break;
                case 'include-pubDate':
                    if ($gif_checked_pubDate) {
                        imagettftext($image, $gif_range_pubDate, 0, 20, $y + $text_height, $couleur_date, $font, $pubDate);
                        $y += $text_height + $base_margin;
                    }
                    break;
            }
        }

        if ($user['status'] === "user") {
            $poweredByText = "Powered by GIF Project";
            $font_size_poweredby = 8;
            $gray_color = imagecolorallocate($image, 128, 128, 128);

            $bbox_poweredby = imagettfbbox($font_size_poweredby, 0, $font, $poweredByText);
            $width_poweredby = $bbox_poweredby[2] - $bbox_poweredby[0];
            $height_poweredby = $bbox_poweredby[1] - $bbox_poweredby[7];

            $x_poweredby = 750 - $width_poweredby - 10; // 5 pixels de marge à droite $max_width remplacé par 750
            $y_poweredby = $total_height - $height_poweredby - 5; // 5 pixels de marge en bas

            imagettftext($image, $font_size_poweredby, 0, $x_poweredby, $y_poweredby, $gray_color, $font, $poweredByText);
        }

        imagepng($image, "fixedGifImages/image" . $i . '_' . $user['username'] . $string_gif . ".png");
        imagedestroy($image);
    }

    $gifCreator = new GifCreator();

    // Définir le chemin vers les images
    $frames = array(
        "fixedGifImages/image0_" . $user['username'] . $string_gif . ".png",
        "fixedGifImages/image1_" . $user['username'] . $string_gif . ".png",
        "fixedGifImages/image2_" . $user['username'] . $string_gif . ".png",
        "fixedGifImages/image3_" . $user['username'] . $string_gif . ".png",
        "fixedGifImages/image4_" . $user['username'] . $string_gif . ".png"
    );
    $durations = array(200, 200, 200, 200, 200); // Chaque image sera affichée pendant 100ms
    $gifCreator->create($frames, $durations);

    $gifData = $gifCreator->getGif();
    date_default_timezone_set('Europe/Paris');
    $customDateFormat = date('d_m_Y_H_i');
    $customFileName = "fixedGif/gif_". $user['username'] . $string_gif . ".gif";

    file_put_contents($customFileName, $gifData);

    $fixedGifPath = $customFileName;
}