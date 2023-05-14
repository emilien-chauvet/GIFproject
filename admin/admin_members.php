<?php
session_start();
include "../db.php";

// Vérification de l'authentification et du statut d'administrateur
if (!isset($_SESSION['user_id']) || $_SESSION['status'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Récupérez la liste des utilisateurs
$sql = "SELECT * FROM users ORDER BY id";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
$users = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Récupérer le nombre total d'utilisateurs
$sql = "SELECT COUNT(*) as count FROM users";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$totalUsers = $row['count'];

$usersPerPage = 10; // Vous pouvez ajuster cette valeur en fonction de vos préférences
// Calculer le nombre total de pages
$totalPages = ceil($totalUsers / $usersPerPage);

// Récupérer la page actuelle à partir de l'URL (ou définir la page 1 par défaut)
if (isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0 && $_GET['page'] <= $totalPages) {
    $currentPage = $_GET['page'];
} else {
    $currentPage = 1;
}
// Calculer l'offset pour la requête SQL
$offset = ($currentPage - 1) * $usersPerPage;

// Récupérer les utilisateurs de la page actuelle
$sql = "SELECT * FROM users ORDER BY id LIMIT ? OFFSET ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $usersPerPage, $offset);
$stmt->execute();
$result = $stmt->get_result();
$users = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$search = "";
if (isset($_GET['search'])) {
    $search = trim($_GET['search']);
}

if ($search) {
    $sql = "SELECT COUNT(*) as count FROM users WHERE username LIKE ?";
    $stmt = $conn->prepare($sql);
    $searchWildcard = "%" . $search . "%";
    $stmt->bind_param("s", $searchWildcard);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $totalUsers = $row['count'];

    $sql = "SELECT * FROM users WHERE username LIKE ? ORDER BY id LIMIT ? OFFSET ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sii", $searchWildcard, $usersPerPage, $offset);
} else {
    $sql = "SELECT COUNT(*) as count FROM users";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $totalUsers = $row['count'];

    $sql = "SELECT * FROM users ORDER BY id LIMIT ? OFFSET ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $usersPerPage, $offset);
}

$stmt->execute();
$result = $stmt->get_result();
$users = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
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
                <h1>Here is the list of users:</h1>
                <form action="admin_members.php" method="get" class="search-form">
                    <input type="text" name="search" placeholder="Find username">
                    <input type="submit" value="Search">
                </form>
                <table>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>e-mail</th>
                        <th>Status</th>
                        <th>Registration date</th>
                        <th>Actions</th>
                    </tr>
                    <?php foreach ($users as $user) { ?>
                        <tr>
                            <td><?php echo $user['id']; ?></td>
                            <td><?php echo $user['username']; ?></td>
                            <td><?php echo $user['email']; ?></td>
                            <td><?php echo $user['status']; ?></td>
                            <td><?php echo $user['creation_date']; ?></td>
                            <td>
                                <a href="edit_user.php?id=<?php echo $user['id']; ?>">Edit</a>
                                <a href="admin_user_gifs.php?user_id=<?php echo $user['id']; ?>">GIF</a>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
            </div>
            <div class="pagination">
                <?php if ($totalUsers > $usersPerPage) { ?>
                    <div class="pagination">
                        <?php for ($i = 1; $i <= $totalPages; $i++) { ?>
                            <a href="?search=<?php echo urlencode($search); ?>&page=<?php echo $i; ?>" class="<?php echo ($i == $currentPage) ? 'active' : ''; ?>"><?php echo $i; ?></a>
                        <?php } ?>
                    </div>
                <?php } ?>

            </div>
        </div>
        <?php include_once 'admin_footer.php' ?>
    </body>

</html>