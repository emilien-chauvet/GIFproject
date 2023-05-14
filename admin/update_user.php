<?php
session_start();
include "../db.php";

// Vérification de l'authentification et du statut d'administrateur
if (!isset($_SESSION['user_id']) || $_SESSION['status'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = $_POST['id'];
    $username = trim(htmlspecialchars($_POST['username']));
    $email = trim(htmlspecialchars($_POST['email']));
    $status = htmlspecialchars($_POST['status']);

    // Vérifier si les données sont valides
    if (empty($userId) || empty($username) || empty($email) || empty($status)) {
        die('Erreur: Tous les champs doivent être remplis.');
    }

    // Mettre à jour les informations de l'utilisateur dans la base de données
    $sql = "UPDATE users SET username = ?, email = ?, status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $username, $email, $status, $userId);
    $stmt->execute();
    $stmt->close();

    // Rediriger vers la page d'administration avec un message de succès
    $_SESSION['admin_success_message'] = "User information was updated successfully.";
    header("Location: admin_members.php");
    exit();
} else {
    // Rediriger vers la page d'administration si la méthode n'est pas POST
    header("Location: admin_members.php");
    exit();
}
?>
