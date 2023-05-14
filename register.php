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

    $string_gif = bin2hex(random_bytes(12));
    $activation_code = bin2hex(random_bytes(16));
    $username = trim(htmlspecialchars(filter_var($_POST['username'], FILTER_SANITIZE_STRING)));
    $email = trim(htmlspecialchars(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL)));
    $password = htmlspecialchars($_POST['password']);
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $status = "user";
    $is_activated = 0;

    // Vérifier si le nom d'utilisateur existe déjà
    $sql = "SELECT id FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $error_message = "Nom d'utilisateur déjà pris.";
        $stmt->close();
    } else {
        // Vérifier si l'email existe déjà
        $sql = "SELECT id FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $error_message = "Adresse e-mail déjà prise.";
            $stmt->close();
        } else {
            $sql = "INSERT INTO users (username, email, password, status, string_gif, activation_code, is_activated) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssssi", $username, $email, $hashed_password, $status, $string_gif, $activation_code, $is_activated);
            $stmt->execute();
            $user_id = $conn->insert_id; // Récupérez l'ID de l'utilisateur nouvellement inscrit
            $stmt->close();

            // Envoyer un email avec le lien d'activation
            $subject = "Activate your account";
            $message = "Click on the following link to activate your account:";
            $message .= "http://localhost/GIFproject/activate.php?code=" . urlencode($activation_code);
            $headers = "From: no-reply@yourwebsite.com\r\n";
            $headers .= "Content-Type: text/plain; charset=utf-8\r\n";

            mail($email, $subject, $message, $headers);

            // Connectez l'utilisateur et redirigez-le vers la page d'index
            $_SESSION['user_id'] = $user_id;
            header("Location: index.php");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Register</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <?php include_once 'includes/header.php' ?>
        <div class="container">
            <div class="wrapper__register">
                <h1>Register</h1>
                <?php if (!empty($error_message)): ?>
                    <div class="error-message">
                        <?php echo $error_message; ?>
                    </div>
                <?php endif; ?>
                <form method="POST">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <label>Username:</label>
                    <input type="text" name="username" required>
                    <label>e-mail:</label>
                    <input type="email" name="email" required>
                    <label>Password:</label>
                    <input type="password" name="password" required>
                    <input type="submit" value="Register">
                </form>
            </div>
        </div>
        <?php include_once 'includes/footer.php' ?>
    </body>
</html>