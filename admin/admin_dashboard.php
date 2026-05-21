<?php
session_start();

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/db.php';

if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'admin') {
    header('Location: /login.php');
    exit();
}
?>



<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin - PromoUnité</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/header.css">
    <link rel="stylesheet" href="/assets/css/admin.css">
    <link rel="stylesheet" href="/assets/css/admin_responsive.css">
    <link rel="stylesheet" href="/assets/css/footer.css">
    
    
</head>
<body class="admin-page">

<?php include('../templates/header.php'); ?>

<div class="container dashboard">
    <h3>Dashboard Admin</h3>
    <p>Gestion de la plateforme</p>

    <div class="cards">
                   
                <div class="card">
                    <i class="fas fa-file-alt"></i>
                    <h4>Documents</h4>
                    <span class="badge">0</span>
                    <a href="admin_documents.php">Accéder</a>
                </div>

                <div class="card">
                    <i class="fas fa-newspaper"></i>
                    <h4>Actualités</h4>
                    <a href="admin_actualites.php">Accéder</a>
                </div>

                <div class="card">
                    <i class="fas fa-bullhorn"></i>
                    <h4>Annonces</h4>
                    <a href="admin_annonces.php">Accéder</a>
                </div>

                <div class="card">
                    <i class="fas fa-comments"></i>
                    <h4>Avis</h4>
                    <a href="admin_avis.php">Accéder</a>
                </div>

                <div class="card">
                    <i class="fas fa-chart-line"></i>
                    <h4>Statistiques</h4>
                    <a href="admin_statistique.php">Accéder</a>
                </div>

    </div>
</div>

<?php include('../templates/footer.php'); ?>

<!-- JS global + admin -->
<script src="../assets/js/main.js"></script>
<script src="../assets/js/admin.js"></script>
</body>
</html>
