<?php
session_start();
require_once '../db.php'; // Incluez votre fichier de configuration de base de données ici

// Vérification de l'authentification et du statut d'administrateur
if (!isset($_SESSION['user_id']) || $_SESSION['status'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: admin_dashboard.php");
    exit();
}

$ticket_id = $_GET['id'];

// Récupérez les détails du ticket
$query = "SELECT tickets.id, tickets.subject, tickets.message, tickets.status, tickets.created_at, users.username, users.email FROM tickets JOIN users ON tickets.user_id = users.id WHERE tickets.id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $ticket_id);
$stmt->execute();
$result = $stmt->get_result();
$ticket = $result->fetch_assoc();

if (!$ticket) {
    header("Location: admin_dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Admin dashboard</title>
        <link rel="stylesheet" href="admin_style.css">
    </head>
    <body>
        <?php include_once 'admin_header.php' ?>
        <div class="container">
            <h1>Ticket details</h1>
            <p><strong>ID:</strong> <?php echo $ticket['id']; ?></p>
            <p><strong>Subject:</strong> <?php echo $ticket['subject']; ?></p>
            <p><strong>Username:</strong> <?php echo $ticket['username']; ?></p>
            <p><strong>Status:</strong> <?php echo $ticket['status']; ?></p>
            <p><strong>Creation date:</strong> <?php echo $ticket['created_at']; ?></p>
            <p><strong>Message:</strong></p>
            <p><?php echo nl2br($ticket['message']); ?></p>
            <p><strong>e-mail :</strong> <?php echo $ticket['email']; ?></p>
            <!-- Ajoutez ici des fonctionnalités supplémentaires, par exemple un formulaire pour répondre au ticket ou modifier le statut -->
        </div>
        <?php include_once 'admin_footer.php' ?>
    </body>
</html>