<?php
session_start();

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/db.php';

if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'admin') {
    header('Location: /login.php');
    exit();
}


/* =========================
   📊 STATS
========================= */

$nb_docs = $pdo->query("
    SELECT 
        (SELECT COUNT(*) FROM cours) +
        (SELECT COUNT(*) FROM td) +
        (SELECT COUNT(*) FROM evaluations)
")->fetchColumn();

$nb_users = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();

$nb_comments = $pdo->query("SELECT COUNT(*) FROM comments")->fetchColumn();

$nb_downloads = 0; // futur module

?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Admin - Statistiques</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="/assets/css/style.css">
        <link rel="stylesheet" href="/assets/css/header.css">
        <link rel="stylesheet" href="../assets/css/admin.css">
        <link rel="stylesheet" href="../assets/css/admin_responsive.css">
        <link rel="stylesheet" href="/assets/css/footer.css">
</head>

<body class="admin-page">

<?php include('../templates/header.php'); ?>

<div class="container">

    <h2>📊 Dashboard Statistiques</h2>

    <div class="stats-box">

        <div class="stat-card">📄 Documents <br><strong><?= $nb_docs ?></strong></div>

        <div class="stat-card">👥 Utilisateurs <br><strong><?= $nb_users ?></strong></div>

        <div class="stat-card">💬 Commentaires <br><strong><?= $nb_comments ?></strong></div>

        <div class="stat-card">⬇️ Téléchargements <br><strong><?= $nb_downloads ?></strong></div>

    </div>

</div>

<?php include('../templates/footer.php'); ?>
<!-- JS global + admin -->
<script src="../assets/js/main.js"></script>
<script src="../assets/js/admin.js"></script>
</body>
</html>
