<?php
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "gif-project";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}

$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
}

function getUserFixedGifs($user_id) {
    global $conn;

    $stmt = $conn->prepare("SELECT * FROM fixed_urls WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $gifs_fixes = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    return $gifs_fixes;
}

function getFixedGifPath($gif_id) {
    global $conn;

    $stmt = $conn->prepare("SELECT fixed_gif FROM fixed_urls WHERE id = ?");
    $stmt->bind_param("i", $gif_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $fixedGifPath = $row['fixed_gif'];
    $stmt->close();

    return $fixedGifPath;
}

function getFixedGifData($gif_id) {
    global $conn;

    $stmt = $conn->prepare("SELECT * FROM fixed_urls WHERE id = ?");
    $stmt->bind_param("i", $gif_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $gif_data = $result->fetch_assoc();
    $stmt->close();

    return $gif_data;
}

function updateFixedGifData($gif_id, $fixed_url, $fixedGifPath, $redirect_url, $gif_font, $background_color, $gif_checked_source, $gif_range_source, $gif_color_source, $gif_checked_title, $gif_range_title, $gif_color_title, $gif_checked_description, $gif_range_description, $gif_color_description, $gif_checked_category, $gif_range_category, $gif_color_category, $gif_checked_author, $gif_range_author, $gif_color_author, $gif_checked_pubDate, $gif_range_pubDate, $gif_color_pubDate, $order_string, $update_frequency) {
    global $conn;

    $stmt = $conn->prepare("UPDATE fixed_urls SET fixed_url = ?, fixed_gif = ?, redirect_url = ?, gif_font = ?, gif_bg_color = ?, gif_checked_source = ?, gif_range_source = ?, gif_color_source = ?, gif_checked_title = ?, gif_range_title = ?, gif_color_title = ?, gif_checked_description = ?, gif_range_description = ?, gif_color_description = ?, gif_checked_category = ?, gif_range_category = ?, gif_color_category = ?, gif_checked_author = ?, gif_range_author = ?, gif_color_author = ?, gif_checked_date = ?, gif_range_date = ?, gif_color_date = ?, gif_order_string = ?, gif_update = ? WHERE id = ?");
    $stmt->bind_param("sssssiisiisiisiisiisiisssi", $fixed_url, $fixedGifPath, $redirect_url, $gif_font, $background_color, $gif_checked_source, $gif_range_source, $gif_color_source, $gif_checked_title, $gif_range_title, $gif_color_title, $gif_checked_description, $gif_range_description, $gif_color_description, $gif_checked_category, $gif_range_category, $gif_color_category, $gif_checked_author, $gif_range_author, $gif_color_author, $gif_checked_pubDate, $gif_range_pubDate, $gif_color_pubDate, $order_string, $update_frequency, $gif_id);
    $stmt->execute();
    $stmt->close();
}

function createFixedGif($user_id, $fixed_url, $fixedGifPath, $redirect_url, $gif_font, $background_color, $gif_checked_source, $gif_range_source, $gif_color_source, $gif_checked_title, $gif_range_title, $gif_color_title, $gif_checked_description, $gif_range_description, $gif_color_description, $gif_checked_category, $gif_range_category, $gif_color_category, $gif_checked_author, $gif_range_author, $gif_color_author, $gif_checked_pubDate, $gif_range_pubDate, $gif_color_pubDate, $order_string, $string_gif, $update_frequency) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO fixed_urls (user_id, fixed_url, fixed_gif, redirect_url, gif_font, gif_bg_color, gif_checked_source, gif_range_source, gif_color_source, gif_checked_title, gif_range_title, gif_color_title, gif_checked_description, gif_range_description, gif_color_description, gif_checked_category, gif_range_category, gif_color_category, gif_checked_author, gif_range_author, gif_color_author, gif_checked_date, gif_range_date, gif_color_date, gif_order_string, string_gif, gif_update) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssssiisiisiisiisiisiissss", $user_id, $fixed_url, $fixedGifPath, $redirect_url, $gif_font, $background_color, $gif_checked_source, $gif_range_source, $gif_color_source, $gif_checked_title, $gif_range_title, $gif_color_title, $gif_checked_description, $gif_range_description, $gif_color_description, $gif_checked_category, $gif_range_category, $gif_color_category, $gif_checked_author, $gif_range_author, $gif_color_author, $gif_checked_pubDate, $gif_range_pubDate, $gif_color_pubDate, $order_string, $string_gif, $update_frequency);
    $stmt->execute();
    $stmt->close();
}