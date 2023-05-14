<?php
session_start();
require_once '../db.php'; // Incluez votre fichier de configuration de base de données ici

// Vérification de l'authentification et du statut d'administrateur
if (!isset($_SESSION['user_id']) || $_SESSION['status'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $ticket_id = $_GET['id'];

    // Supprimez le ticket de la base de données
    $query = "DELETE FROM tickets WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $ticket_id);
    $stmt->execute();

    // Redirigez vers la page d'administration des tickets
    header("Location: admin_tickets.php");
    exit();
} else {
    // Si l'ID du ticket n'est pas défini ou n'est pas numérique, redirigez vers la page d'administration des tickets
    header("Location: admin_tickets.php");
    exit();
}
?>
