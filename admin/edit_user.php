<?php
session_start();
include "../db.php";

// Vérification de l'authentification et du statut d'administrateur
if (!isset($_SESSION['user_id']) || $_SESSION['status'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Récupérer l'ID de l'utilisateur à partir de l'URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $userId = $_GET['id'];
} else {
    // Rediriger vers la page d'administration si l'ID n'est pas défini ou n'est pas valide
    header("Location: admin_members.php");
    exit();
}

$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user) {
    // Rediriger vers la page d'administration si l'utilisateur n'est pas trouvé
    header("Location: admin_members.php");
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
            <div class="wrapper__dashboard">
                <h1>Edit user: <?php echo $user['username']; ?></h1>
                <form method="POST" action="update_user.php">
                    <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
                    <label>Username:</label>
                    <input type="text" name="username" value="<?php echo $user['username']; ?>" required>
                    <label>e-mail:</label>
                    <input type="email" name="email" value="<?php echo $user['email']; ?>" required>
                    <label>Status:</label>
                    <select name="status" required>
                        <option value="user" <?php echo ($user['status'] == 'user') ? 'selected' : ''; ?>>User</option>
                        <option value="premium" <?php echo ($user['status'] == 'premium') ? 'selected' : ''; ?>>Premium</option>
                        <option value="admin" <?php echo ($user['status'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
                    </select>
                    <!-- Ajoutez d'autres champs de formulaire si nécessaire -->
                    <input type="submit" value="Save changes">
                </form>
            </div>
        </div>
        <?php include_once 'admin_footer.php' ?>
    </body>
</html>