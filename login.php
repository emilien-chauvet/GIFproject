<?php
session_start();
include "db.php";

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

// Génération du jeton CSRF
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Vérification du jeton CSRF
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die('Invalid CSRF token');
    }
    $usernameOrEmail = trim(htmlspecialchars($_POST['username']));
    $password = htmlspecialchars($_POST['password']);

    // Recherche de l'utilisateur par nom d'utilisateur
    $query = "SELECT * FROM users WHERE username = ? OR email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $usernameOrEmail, $usernameOrEmail);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        // Vérification du statut de l'utilisateur
        if ($user['status'] == 'user' || $user['status'] == 'premium') {
            // Connexion de l'administrateur
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['is_activated'] = $user['is_activated'];
            $_SESSION['status'] = $user['status'];
            header("Location: dashboard.php");
            exit();
        } else {
            echo "You are not authorized to access this page.";
        }
    } else {
        // Informations de connexion incorrectes
        echo "Username or password is incorrect.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Login</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <?php include_once 'includes/header.php' ?>
        <div class="container">
            <div class="wrapper__login">
                <h1>Connexion</h1>
                <form method="POST">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <label>Username or e-mail:</label>
                    <input type="text" name="username" required>
                    <label>Password:</label>
                    <input type="password" name="password" required>
                    <input type="submit" value="Login">
                </form>
            </div>
        </div>
        <?php include_once 'includes/footer.php' ?>
    </body>
</html>