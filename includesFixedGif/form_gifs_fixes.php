<?php
include "functions.php";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function displayForm($gif_data, $user, $update_frequency, $mode = 'update') {
    $order_string = isset($gif_data['gif_order_string']) ? $gif_data['gif_order_string'] : "";
    ?>
    <form method="post">
        <!-- Ajoutez vos champs de formulaire ici, en utilisant les données de $gif_data pour préremplir les valeurs -->
        <label for="fixed_url">Animated GIF RSS feed URL:</label>
        <input id="fixed_url" type="url" name="fixed_url" value="<?php echo $gif_data['fixed_url'] ?? ''; ?>">

        <input id="generate-informations" type="button" value="See the information offered by this link" onclick="fetchRSSInfoFixed()">
        <div id="rss-info-container"></div>

        <label for="redirect_url">I want my GIF to redirect to the link:</label>
        <input type="url" name="redirect_url" value="<?php echo $gif_data['redirect_url'] ?? ''; ?>">

        <label for="font">Font:</label>
        <select name="font">
            <option value="arial.ttf" <?php echo selected("arial.ttf", $gif_data['gif_font'] ?? 'arial.ttf'); ?>>Arial</option>
            <option value="lato.ttf" <?php echo selected("lato.ttf", $gif_data['gif_font'] ?? 'arial.ttf'); ?>>Lato</option>
            <option value="montserrat.ttf" <?php echo selected("montserrat.ttf", $gif_data['gif_font'] ?? 'arial.ttf'); ?>>MontSerrat</option>
            <option value="opensans.ttf" <?php echo selected("opensans.ttf", $gif_data['gif_font'] ?? 'arial.ttf'); ?>>OpenSans</option>
            <option value="sourcesanspro.ttf" <?php echo selected("sourcesanspro.ttf", $gif_data['gif_font'] ?? 'arial.ttf'); ?>>SourceSansPro</option>
            <option value="roboto.ttf" <?php echo selected("roboto.ttf", $gif_data['gif_font'] ?? 'arial.ttf'); ?>>Roboto</option>
            <option value="josefinsans.ttf" <?php echo selected("josefinsans.ttf", $gif_data['gif_font'] ?? 'arial.ttf'); ?>>JosefinSans</option>
            <option value="cabin.ttf" <?php echo selected("cabin.ttf", $gif_data['gif_font'] ?? 'arial.ttf'); ?>>Cabin</option>
            <option value="secularone.ttf" <?php echo selected("secularone.ttf", $gif_data['gif_font'] ?? 'arial.ttf'); ?>>SecularOne</option>
        </select>

        <label for="background_color">Background color:</label>
        <input type="color" name="background_color" value="<?php echo $gif_data['gif_bg_color'] ?? '#FFFFFF'; ?>">

        <div class="include-section">
            <label for="include-source">Include source</label>
            <input type="checkbox" class="updateElement" id="include-source" name="include-source" <?php echo ($gif_data['gif_checked_source'] ?? 0) ? 'checked' : ''; ?>>
            <label for="font-size-source">Source font size</label>
            <input type="range" name="font-size-source" min="5" max="15" value="<?php echo $gif_data['gif_range_source'] ?? '10'; ?>">
            <label for="color-source">Source color</label>
            <input type="color" name="color-source" value="<?php echo $gif_data['gif_color_source'] ?? '#000000'; ?>">
        </div>

        <div class="include-section">
            <label for="include-title">Include title</label>
            <input type="checkbox" class="updateElement" id="include-title" name="include-title" <?php echo ($gif_data['gif_checked_title'] ?? 0) ? 'checked' : ''; ?>>
            <label for="font-size-title">Title font size</label>
            <input type="range" name="font-size-title" min="5" max="15" value="<?php echo $gif_data['gif_range_title'] ?? '10'; ?>">
            <label for="color-title">Title color</label>
            <input type="color" name="color-title" value="<?php echo $gif_data['gif_color_title'] ?? '#000000'; ?>">
        </div>

        <div class="include-section">
            <label for="include-description">Include description</label>
            <input type="checkbox" class="updateElement" id="include-description" name="include-description" <?php echo ($gif_data['gif_checked_description'] ?? 0) ? 'checked' : ''; ?>>
            <label for="font-size-description">Description font size</label>
            <input type="range" name="font-size-description" min="5" max="15" value="<?php echo $gif_data['gif_range_description'] ?? '10'; ?>">
            <label for="color-description">Description color</label>
            <input type="color" name="color-description" value="<?php echo $gif_data['gif_color_description'] ?? '#000000'; ?>">
        </div>

        <div class="include-section">
            <label for="include-category">Include category</label>
            <input type="checkbox" class="updateElement" id="include-category" name="include-category" <?php echo ($gif_data['gif_checked_category'] ?? 0) ? 'checked' : ''; ?>>
            <label for="font-size-category">Category font size</label>
            <input type="range" name="font-size-category" min="5" max="15" value="<?php echo $gif_data['gif_range_category'] ?? '10'; ?>">
            <label for="color-category">Category color</label>
            <input type="color" name="color-category" value="<?php echo $gif_data['gif_color_category'] ?? '#000000'; ?>">
        </div>

        <div class="include-section">
            <label for="include-author">Include author</label>
            <input type="checkbox" class="updateElement" id="include-author" name="include-author" <?php echo ($gif_data['gif_checked_author'] ?? 0) ? 'checked' : ''; ?>>
            <label for="font-size-author">Author font size</label>
            <input type="range" name="font-size-author" min="5" max="15" value="<?php echo $gif_data['gif_range_author'] ?? '10'; ?>">
            <label for="color-author">Author color</label>
            <input type="color" name="color-author" value="<?php echo $gif_data['gif_color_author'] ?? '#000000'; ?>">
        </div>

        <div class="include-section">
            <label for="include-pubDate">Include date</label>
            <input type="checkbox" class="updateElement" id="include-pubDate" name="include-pubDate" <?php echo ($gif_data['gif_checked_date'] ?? 0) ? 'checked' : ''; ?>>
            <label for="font-size-pubDate">Date font size</label>
            <input type="range" name="font-size-pubDate" min="5" max="15" value="<?php echo $gif_data['gif_range_date'] ?? '10'; ?>">
            <label for="color-pubDate">Date color</label>
            <input type="color" name="color-pubDate" value="<?php echo $gif_data['gif_color_date'] ?? '#000000'; ?>">
        </div>

        <div id="selected_elements" data-order="<?php echo $order_string; ?>"></div>
        <input type="hidden" name="order">

        <div>
            <label for="update-weekly">Update weekly</label>
            <input type="checkbox" id="update-weekly" name="update-weekly" <?php echo ($update_frequency === 'weekly') ? 'checked' : ''; ?>>
        </div>

        <div>
            <label for="update-daily">Update daily</label>
            <input type="checkbox" id="update-daily" name="update-daily" <?php echo ($user['status'] === 'user') ? 'disabled' : ''; ?> <?php echo ($update_frequency === 'daily') ? 'checked' : ''; ?>>
        </div>

        <?php
        if ($mode === 'create') {
        echo '<button type="submit" name="create_gif">Create the GIF</button>';
        } else {
        echo '<button type="submit" name="update_gif">Update GIF</button>';
        }
        ?>
    </form>
    <?php
}