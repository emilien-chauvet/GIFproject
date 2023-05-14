<?php
session_start();
include "db.php";

if (isset($_GET['code'])) {
    $activation_code = $_GET['code'];
    $sql = "UPDATE users SET is_activated = 1 WHERE activation_code = ? AND is_activated = 0";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $activation_code);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "Your account has been successfully activated. You can now <a href='http://localhost/GIFproject/account/login.php'>log in</a>.";
        // On force la déconnexion pour obliger l'utilisateur à se reconnecter afin de masquer le bandeau rouge
        session_destroy();
    } else {
        echo "The activation code is invalid or has already been used.";
    }
    $stmt->close();
} else {
    echo "No activation code was provided.";
}
?>
