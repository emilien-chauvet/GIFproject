<?php
session_start();
require_once "includesFixedGif/db_gifs_fixes.php";
include "includes/confirmationBanner.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Récupère le $user_id et permet d'empêcher un utilisateur non connecté d'accéder à la page
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    // L'utilisateur n'est pas connecté, redirection vers page index
    header("Location: index.php");
    exit;
}

// Récupérez l'historique des GIFs générés par l'utilisateur
$userId = $_SESSION['user_id'];
$sql = "SELECT * FROM gifs WHERE user_id = ? ORDER BY creation_date DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$gifs = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$sql_redirect_url = "SELECT redirect_url FROM fixed_urls WHERE user_id = ?";
$stmt_redirect_url = $conn->prepare($sql_redirect_url);
$stmt_redirect_url->bind_param("i", $userId);
$stmt_redirect_url->execute();
$result_redirect_url = $stmt_redirect_url->get_result();
if ($result_redirect_url->num_rows > 0) {
    $row = $result_redirect_url->fetch_assoc();
    $redirectUrl = $row['redirect_url'];
} else {
    $redirectUrl = ''; // ou définissez une valeur par défaut si nécessaire
}
$stmt_redirect_url->close();

$gifs_fixes = getUserFixedGifs($user_id);
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Dashboard</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <?php include_once 'includes/header.php' ?>
        <div class="container">
            <div class="wrapper__dashboard">
                <div class="inside__wrapper__dashboard__left">
                    <h1>Welcome, <?php echo $user['username']; ?> !</h1>
                    <p>Your role is : <?php echo $user['status']; ?></p>
                    <p>To become a premium member, <a href="checkout.php">click here</a></p>
                    <p>e-mail : <?php echo $user['email']; ?></p>
                    <p>My GIF :</p>
                    <?php foreach ($gifs_fixes as $fixed_gif) : ?>
                    <div class="inside__wrapper__dashboard__fixedgif">
                        <div>
                            <a href="single_fixed_gif.php?id=<?php echo $fixed_gif['id'] ?>">
                                <img src="http://localhost/gifproject/<?php echo $fixed_gif['fixed_gif'] ?>">
                            </a>
                        </div>
                        <div>
                            <a title="Edit this GIF" class="edit__item__gif" href="single_fixed_gif.php?id=<?php echo $fixed_gif['id']; ?>"></a>
                            <a title="Add this GIF" class="add__item__gif" href="javascript:void(0);" onclick="showModal('http://localhost/GIFproject/fixedGif/gif_<?php echo $user['username'] . $fixed_gif['string_gif'] ?>.gif', '<?php echo htmlspecialchars($fixed_gif['redirect_url']); ?>');"></a>
                            <a title="Delete this GIF" class="delete__item__gif" href="delete_fixed_gif.php?id=<?php echo $fixed_gif['id']; ?>" onclick="return confirm('Are you sure you want to delete this GIF?')"></a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <div class="inside__wrapper__dashboard_right">
                    <p><a class="logout__button" href="logout.php">Logout</a></p>
                </div>
            </div>
            <div class="dashboard__wrapper__gifs">
                <p>Your different GIF :</p>
                <?php foreach ($gifs as $gif): ?>
                <div class="dashboard__wrapper__item__gif">
                    <a href="">
                        <img class="item__gif" src="http://localhost/GIFproject/<?php echo htmlspecialchars($gif['gif_path']); ?>">
                    </a>
                    <div class="inside__wrapper__item__gif">
                        <div>
                            <a title="Add this GIF" class="add__item__gif" href="javascript:void(0);" onclick="showModal('http://localhost/GIFproject/<?php echo htmlspecialchars($gif['gif_path']); ?>');"></a>
                        </div>
                        <div>
                            <a title="Download this GIF" class="download__item__gif" href="http://localhost/GIFproject/<?php echo htmlspecialchars($gif['gif_path']); ?>" download="<?php echo basename($gif['gif_path']); ?>"></a>
                        </div>
                        <div>
                            <a title="Delete this GIF" class="delete__item__gif" href="delete_gif.php?id=<?php echo $gif['id']; ?>" onclick="return confirm('Are you sure you want to delete this GIF?')"></a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div id="myModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <p id="modal-text">To embed this GIF, copy and paste the following HTML code:</p>
                <textarea id="embed-code" readonly></textarea>
                <button id="copy-button">Copier</button>
            </div>
        </div>
        <?php include_once 'includes/footer.php' ?>
        <script src="assets/js/dashboard.js"></script>
    </body>
</html>