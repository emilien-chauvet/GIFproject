<?php
session_start();
require_once '../db.php';

// Vérification de l'authentification et du statut d'administrateur
if (!isset($_SESSION['user_id']) || $_SESSION['status'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Récupérez tous les tickets de la base de données
$query = "SELECT tickets.id, tickets.subject, tickets.message, tickets.status, tickets.created_at, users.username FROM tickets JOIN users ON tickets.user_id = users.id ORDER BY tickets.created_at DESC";
$result = $conn->query($query);

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
            <h1>List of tickets</h1>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Subject</th>
                        <th>Username</th>
                        <th>Status</th>
                        <th>Creation date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($ticket = $result->fetch_assoc()) : ?>
                        <tr>
                            <td><?php echo $ticket['id']; ?></td>
                            <td><?php echo $ticket['subject']; ?></td>
                            <td><?php echo $ticket['username']; ?></td>
                            <td><?php echo $ticket['status']; ?></td>
                            <td><?php echo $ticket['created_at']; ?></td>
                            <td>
                                <a href="ticket_details.php?id=<?php echo $ticket['id']; ?>">View ticket</a>
                                <a href="delete_ticket.php?id=<?php echo $ticket['id']; ?>" onclick="return confirm('Are you sure you want to delete this ticket?')">Delete ticket</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php include_once 'admin_footer.php' ?>
    </body>
</html>
