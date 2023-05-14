<?php
session_start();
include "../db.php";
include "../functions.php";

// Vérification de l'authentification et du statut d'administrateur
if (!isset($_SESSION['user_id']) || $_SESSION['status'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Admin dashboard</title>
        <link rel="stylesheet" href="admin_style.css">
    </head>
    <body>
        <?php include_once 'admin_header.php' ?>
        <div class="container">
            <table class="activity-log">
                <thead>
                    <tr>
                        <th class="log-header">ID</th>
                        <th class="log-header">User ID</th>
                        <th class="log-header">Action</th>
                        <th class="log-header">Description</th>
                        <th class="log-header">Timestamp</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach (get_activity_logs() as $log) : ?>
                        <tr>
                            <td class="log-cell"><?php echo $log['id'] ?></td>
                            <td class="log-cell"><?php echo $log['user_id'] ?></td>
                            <td class="log-cell"><?php echo $log['action'] ?></td>
                            <td class="log-cell"><?php echo $log['description'] ?></td>
                            <td class="log-cell"><?php echo $log['timestamp'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php include_once 'admin_footer.php' ?>
    </body>

</html>