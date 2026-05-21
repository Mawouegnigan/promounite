<?php
if(session_status() === PHP_SESSION_NONE){
    session_start();
}

$role   = $_SESSION['role'] ?? '';
$prenom = $_SESSION['prenom'] ?? '';
$nom    = $_SESSION['nom'] ?? '';
?>

<!-- CSS GLOBAL -->
<link rel="stylesheet" href="/assets/css/style.css">
<link rel="stylesheet" href="/assets/css/header.css">

<header class="main-header">

    <!-- LOGO -->
    <div class="logo-container">
        <a href="/index.php">
            <img src="/assets/images/logo.png" alt="PromoUnité" class="logo">
        </a>
    </div>

    <!-- MENU MOBILE -->
    <div class="menu-toggle" id="menuToggle">☰</div>

    <!-- NAVIGATION -->
    <nav class="navbar">

        <!-- ACCUEIL -->
        <a href="/index.php">Accueil</a>

        <!-- COURS -->
        <div class="dropdown">
            <a href="#">Cours</a>
            <div class="dropdown-content">
                <a href="/documents.php?type=cours&annee=1">Année 1</a>
                <a href="/documents.php?type=cours&annee=2">Année 2</a>
            </div>
        </div>

        

        <!-- ÉVALUATIONS  et TD-->
        <div class="dropdown">
            <a href="#">Évaluations</a>
            <div class="dropdown-content">

                <div class="subheading">Interros</div>
                <a href="/documents.php?type=eval&annee=1&sub=interro">Année 1</a>
                <a href="/documents.php?type=eval&annee=2&sub=interro">Année 2</a>

                <div class="subheading">Devoirs</div>
                <a href="/documents.php?type=eval&annee=1&sub=devoir">Année 1</a>
                <a href="/documents.php?type=eval&annee=2&sub=devoir">Année 2</a>
                
                
                <!-- TD -->
                <div class="subheading">TD</div>
        		<a href="/documents.php?type=td">TD</a>
             
            </div>
        </div>

        <!-- NORMES (ordre de priorité) -->
        <div class="dropdown">
            <a href="#">Normes</a>
            <div class="dropdown-content">

                <a href="/documents.php?type=normes&cat=constitution">Constitution</a>
                <a href="/documents.php?type=normes&cat=loi">Lois</a>
                <a href="/documents.php?type=normes&cat=decret">Décrets</a>
                <a href="/documents.php?type=normes&cat=arrete">Arrêtés</a>
                <a href="/documents.php?type=normes&cat=circulaire">Circulaires</a>

            </div>
        </div>

        <!-- CARRIÈRE -->
        <a href="/documents.php?type=carriere">Carrière</a>

        <!-- ADMIN -->
        <?php if($role === 'admin'): ?>
        <div class="dropdown">
            <a href="#">Admin</a>
            <div class="dropdown-content">
                <a href="/admin/admin_dashboard.php">Dashboard</a>
                <a href="/admin/admin_documents.php">Documents</a>
                <a href="/admin/admin_actualites.php">Actualités</a>
                <a href="/admin/admin_annonces.php">Annonces</a>
                <a href="/admin/admin_avis.php">Avis</a>
                <a href="/admin/admin_statistique.php">Statistiques</a>
            </div>
        </div>
        <?php endif; ?>

        <!-- PROFIL -->
        <div class="dropdown profile">
            <a href="#">
                <?= ($prenom && $nom) ? htmlspecialchars($prenom.' '.$nom) : 'Mon profil' ?>
            </a>
            <div class="dropdown-content">
                <a href="/profile.php">Mettre à jour</a>
                <a href="/logout.php">Déconnexion</a>
            </div>
        </div>

    </nav>
</header>

<script src="/assets/js/header.js"></script>