<?php
session_start();
include "includesFixedGif/db_gifs_fixes.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $gifId = $_GET['id'];
    $redirectUrl = isset($_GET['redirect']) ? urldecode($_GET['redirect']) : 'http://localhost/GIFproject/admin/admin_user_gifs.php';

    $userId = $_GET['user_id'];

    // Récupérez le chemin du fichier gif et l'username de l'utilisateur
    $sql = "SELECT gifs.*, users.username FROM gifs INNER JOIN users ON gifs.user_id = users.id WHERE gifs.id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $gifId);
    $stmt->execute();
    $result = $stmt->get_result();
    $gif = $result->fetch_assoc();
    $stmt->close();



    // Supprimer le fichier gif
    if (file_exists($gif['gif_path'])) {
        unlink($gif['gif_path']);
    }

    // Supprimez l'entrée dans la base de données
    $sql = "DELETE FROM gifs WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $gifId);
    $stmt->execute();
    $stmt->close();

    if ($user['status'] === "admin") {
        header("Location: " . $redirectUrl);
        exit();
    } else {
        header("Location: dashboard.php");
        exit();
    }
}


