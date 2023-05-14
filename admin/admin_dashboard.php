<?php
session_start();
include "../db.php";
include "../functions.php";

// Vérification de l'authentification et du statut d'administrateur
if (!isset($_SESSION['user_id']) || $_SESSION['status'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Récupérer le nombre total d'utilisateurs
$sqlTotalUsers = "SELECT COUNT(*) as count FROM users";
$resultTotalUsers = $conn->query($sqlTotalUsers);
$rowTotalUsers = $resultTotalUsers->fetch_assoc();
$totalUsers = $rowTotalUsers['count'];

// Récupérer le nombre total de GIFs
$sqlTotalGifs = "SELECT COUNT(*) as count FROM gifs";
$resultTotalGifs = $conn->query($sqlTotalGifs);
$rowTotalGifs = $resultTotalGifs->fetch_assoc();
$totalGifs = $rowTotalGifs['count'];

// Récupérer le nombre total de GIFs fixes
$sqlTotalFixedGifs = "SELECT COUNT(*) as count FROM fixed_urls";
$resultTotalFixedGifs = $conn->query($sqlTotalFixedGifs);
$rowTotalFixedGifs = $resultTotalFixedGifs->fetch_assoc();
$totalFixedGifs = $rowTotalFixedGifs['count'];

// Récupérer le nombre d'utilisateurs inscrits ce mois-ci
$currentMonth = date('Y-m-01');
$sqlUsersThisMonth = "SELECT COUNT(*) as count FROM users WHERE creation_date >= ?";
$stmtUsersThisMonth = $conn->prepare($sqlUsersThisMonth);
$stmtUsersThisMonth->bind_param("s", $currentMonth);
$stmtUsersThisMonth->execute();
$resultUsersThisMonth = $stmtUsersThisMonth->get_result();
$rowUsersThisMonth = $resultUsersThisMonth->fetch_assoc();
$usersThisMonth = $rowUsersThisMonth['count'];
$stmtUsersThisMonth->close();

// Récupérer le nombre total d'utilisateurs "premium"
$sqlTotalPremiumUsers = "SELECT COUNT(*) as count FROM users WHERE status = 'premium'";
$resultTotalPremiumUsers = $conn->query($sqlTotalPremiumUsers);
$rowTotalPremiumUsers = $resultTotalPremiumUsers->fetch_assoc();
$totalPremiumUsers = $rowTotalPremiumUsers['count'];

// Récupérer le nombre total de tickets
$sqlTotalTickets = "SELECT COUNT(*) as count FROM tickets";
$resultTotalTickets = $conn->query($sqlTotalTickets);
$rowTotalTickets = $resultTotalTickets->fetch_assoc();
$totalTickets = $rowTotalTickets['count'];
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
            <div class="wrapper__dashboard">
                <div class="inside__wrapper__dashboard__left">
                    <h1>Welcome, <?php echo $user['username']; ?> !</h1>
                    <p>Your role is : <?php echo $user['status']; ?></p>
                    <p>e-mail : <?php echo $user['email']; ?></p>
                </div>
                <div class="inside__wrapper__dashboard_right">
                    <p><a class="logout__button" href="../logout.php">Logout</a></p>
                </div>
            </div>
            <div class="wrapper__dashboard__statistics">
                <div class="inside__wrapper__dashboard__top">
                    <div>
                        <p>Total number of users:</p>
                        <p><?php echo $totalUsers; ?></p>
                    </div>
                    <div>
                        <p>Users registered this month:</p>
                        <p><?php echo $usersThisMonth; ?></p>
                    </div>
                    <div>
                        <p>Number of GIF:</p>
                        <p><?php echo $totalGifs; ?></p>
                    </div>
                </div>
                <div class="inside__wrapper__dashboard__bottom">
                    <div>
                        <p>Number of fixed GIF:</p>
                        <p><?php echo $totalFixedGifs; ?></p>
                    </div>
                    <div>
                        <p>Number of premium users:</p>
                        <p><?php echo $totalPremiumUsers; ?></p>
                    </div>
                    <div>
                        <p>Number of tickets in progress:</p>
                        <p><?php echo $totalTickets; ?></p>
                    </div>
                </div>
            </div>
        </div>
        <?php include_once 'admin_footer.php' ?>
    </body>
</html>