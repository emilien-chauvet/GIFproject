<?php
session_start();
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
include "db.php";
require_once 'includes/GifCreator.php';
include "includes/confirmationBanner.php";
$isUserConnected = isset($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html>

    <head>
        <meta charset="UTF-8">
        <title>Home</title>
        <link rel="stylesheet" href="style.css">
    </head>

<body>

    <?php include_once 'includes/header.php' ?>

    <div class="container">

        <?php
        if (isset($_SESSION['user_id'])) {
        ?>
            <h1>Welcome to GIF project, <?php echo $user['username']; ?> !</h1>
            <p>An error ? <a style="color: red;" href="ticket.php">Report your problem</a></p>
        <?php
        } else {
            ?>
            <h1>Welcome to GIF project, foreigner !</h1>
            <?php
        }
        ?>

        <form class="form__index-gif" method="post" action="gif.php">
            <label for="rss-link">RSS link :</label>
            <input type="text" id="rss-link" name="rss-link">
            <input id="generate-informations" type="button" id="load-info" value="See the information offered by this link" onclick="fetchRSSInfo()">
            <div id="rss-info-container"></div>

            <label for="font">Font:</label>
            <select name="font" id="font">
                <option value="arial.ttf">Arial</option>
                <option value="lato.ttf">Lato</option>
                <option value="montserrat.ttf">MontSerrat</option>
                <option value="opensans.ttf">OpenSans</option>
                <option value="sourcesanspro.ttf">SourceSansPro</option>
                <option value="roboto.ttf">Roboto</option>
            </select>

            <div class="background-color-section">
                <label for="background-color">Background color:</label>
                <input type="color" id="background-color" name="background-color" value="#FFFFFF">
            </div>

            <div class="include-section">
                <input type="checkbox" id="include-source" name="include-source">
                <label for="include-source">Include source</label>
                <input type="range" id="font-size-source" name="font-size-source" min="5" max="15" value="10" class="font-size-slider" oninput="updateSliderValue(this.id, 'slider-value-source', 'hidden-font-size-source')">
                <input type="text" id="slider-value-source" value="10" disabled>
                <input type="color" id="source-color" name="source-color" value="#000000">
                <input type="hidden" id="hidden-font-size-source" name="hidden-font-size-source" value="10">
            </div>
            <div class="include-section">
                <input type="checkbox" id="include-title" name="include-title">
                <label for="include-title">Include title</label>
                <input type="range" id="font-size-title" name="font-size-title" min="5" max="15" value="10" class="font-size-slider" oninput="updateSliderValue(this.id, 'slider-value-title', 'hidden-font-size-title')">
                <input type="text" id="slider-value-title" value="10" disabled>
                <input type="color" id="title-color" name="title-color" value="#000000">
                <input type="hidden" id="hidden-font-size-title" name="hidden-font-size-title" value="10">
            </div>
            <div class="include-section">
                <input type="checkbox" id="include-description" name="include-description">
                <label for="include-description">Include description</label>
                <input type="range" id="font-size-description" name="font-size-description" min="5" max="15" value="10" class="font-size-slider" oninput="updateSliderValue(this.id, 'slider-value-description', 'hidden-font-size-description')">
                <input type="text" id="slider-value-description" value="10" disabled>
                <input type="color" id="description-color" name="description-color" value="#000000">
                <input type="hidden" id="hidden-font-size-description" name="hidden-font-size-description" value="10">
            </div>
            <div class="include-section">
                <input type="checkbox" id="include-category" name="include-category">
                <label for="include-category">Include category</label>
                <input type="range" id="font-size-category" name="font-size-category" min="5" max="15" value="10" class="font-size-slider" oninput="updateSliderValue(this.id, 'slider-value-category', 'hidden-font-size-category')">
                <input type="text" id="slider-value-category" value="10" disabled>
                <input type="color" id="category-color" name="category-color" value="#000000">
                <input type="hidden" id="hidden-font-size-category" name="hidden-font-size-category" value="10">
            </div>
            <div class="include-section">
                <input type="checkbox" id="include-author" name="include-author">
                <label for="include-author">Include author</label>
                <input type="range" id="font-size-author" name="font-size-author" min="5" max="15" value="10" class="font-size-slider" oninput="updateSliderValue(this.id, 'slider-value-author', 'hidden-font-size-author')">
                <input type="text" id="slider-value-author" value="10" disabled>
                <input type="color" id="author-color" name="author-color" value="#000000">
                <input type="hidden" id="hidden-font-size-author" name="hidden-font-size-author" value="10">
            </div>
            <div class="include-section">
                <input type="checkbox" id="include-pubDate" name="include-pubDate">
                <label for="include-pubDate">Include date</label>
                <input type="range" id="font-size-pubDate" name="font-size-pubDate" min="5" max="15" value="10" class="font-size-slider" oninput="updateSliderValue(this.id, 'slider-value-pubDate', 'hidden-font-size-pubDate')">
                <input type="text" id="slider-value-pubDate" value="10" disabled>
                <input type="color" id="pubDate-color" name="pubDate-color" value="#C3C3C3">
                <input type="hidden" id="hidden-font-size-pubDate" name="hidden-font-size-pubDate" value="10">
            </div>

            <div id="selected_elements"></div>

            <input type="hidden" name="order">

            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

            <?php if ($isUserConnected) { ?>
                <button id="generate-gif" type="submit">Generate the GIF</button>
            <?php } else { ?>
                <button id="generate-gif" type="submit">Preview GIF</button>
            <?php } ?>
        </form>

        <div id="error-message"></div>

    </div>

    <div id="result"></div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/index.js"></script>
    <?php include_once 'includes/footer.php' ?>

</body>
</html>