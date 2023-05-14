<?php
session_start();
include "../db.php";

// Vérification de l'authentification et du statut d'administrateur
if (!isset($_SESSION['user_id']) || $_SESSION['status'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

if (!isset($_GET['user_id'])) {
    header("Location: admin_members.php");
    exit();
}

$userId = $_GET['user_id'];

// Récupérer les entrées de la table users avec $userId
$sql_user = "SELECT * FROM users WHERE id = ?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param("i", $userId);
$stmt_user->execute();
$result_user = $stmt_user->get_result();
$userInfo = $result_user->fetch_assoc();
$stmt_user->close();

// Récupérer les entrées de la table fixed_urls avec $userId
$sql_fixed = "SELECT * FROM fixed_urls WHERE user_id = ?";
$stmt_fixed = $conn->prepare($sql_fixed);
$stmt_fixed->bind_param("i", $userId);
$stmt_fixed->execute();
$result_fixed = $stmt_fixed->get_result();
$fixedGif = array();
while ($row = $result_fixed->fetch_assoc()) {
    $fixedGif[] = $row;
}
$stmt_fixed->close();


// Récupérer les GIFs de l'utilisateur spécifié
$sql = "SELECT * FROM gifs WHERE user_id = ? ORDER BY creation_date DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$gifs = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>GIFs de l'utilisateur</title>
    <link rel="stylesheet" href="admin_style.css">
</head>

<body>
<?php include_once 'admin_header.php' ?>
<div class="container">
    <div class="wrapper__dashboard">
        <h1>GIFs générés par l'utilisateur <?php echo $userInfo['username']; ?></h1>
        <h2>Les GIF fixes</h2>
            <?php foreach ($fixedGif as $gif) { ?>
                <img src="http://localhost/GIFproject/<?php echo $gif['fixed_gif']; ?>">
                <a title="Supprimer ce GIF" class="delete__item__gif" href="../delete_fixed_gif.php?id=<?php echo $gif['id']; ?>&redirect=<?php echo urlencode($_SERVER['REQUEST_URI']); ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce GIF ?')">Supprimer</a>
            <?php } ?>
        <h2>Les autres GIFs</h2>
        <ul>
            <?php foreach ($gifs as $gif) { ?>
                <img src="http://localhost/GIFproject/<?php echo $gif['gif_path']; ?>">
                <a title="Supprimer ce GIF" class="delete__item__gif" href="../delete_gif.php?id=<?php echo $gif['id']; ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce GIF ?')">Supprimer</a>
            <?php } ?>
        </ul>
    </div>
</div>
<?php include_once 'admin_footer.php' ?>
</body>

</html>