<?php
session_start();
require_once 'db.php'; // Incluez votre fichier de configuration de base de données ici

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    echo "You must be logged in to submit a ticket.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    // Insérez le ticket dans la base de données
    $stmt = $conn->prepare("INSERT INTO tickets (user_id, subject, message) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $user_id, $subject, $message);
    $stmt->execute();

    echo "Your ticket has been successfully submitted.";
} else {
    header("Location: index.php"); // Remplacez "index.php" par la page contenant le formulaire de soumission de ticket
}