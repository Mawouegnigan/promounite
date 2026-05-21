<?php
session_start();

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/db.php';

if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'admin') {
    header('Location: /login.php');
    exit();
}



$upload_dir = __DIR__ . '/../assets/uploads/actualites/';

// Ajout d'une actualité
if(isset($_POST['add'])) {
    $titre = $_POST['titre'];
    $ordre = $_POST['ordre'];

    if(isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $filename = time().'_'.basename($_FILES['image']['name']);
        $target = $upload_dir.$filename;
        move_uploaded_file($_FILES['image']['tmp_name'], $target);

        $stmt = $pdo->prepare("INSERT INTO actualites (titre, nom_fichier, ordre, statut) VALUES (?, ?, ?, 'actif')");
        $stmt->execute([$titre, $filename, $ordre]);
        header("Location: admin_actualites.php");
        exit;
    }
}

// Suppression d'une actualité
if(isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $pdo->prepare("SELECT nom_fichier FROM actualites WHERE id=?");
    $stmt->execute([$id]);
    $file = $stmt->fetch(PDO::FETCH_ASSOC)['nom_fichier'];
    if($file && file_exists($upload_dir.$file)) unlink($upload_dir.$file);

    $stmt = $pdo->prepare("DELETE FROM actualites WHERE id=?");
    $stmt->execute([$id]);
    header("Location: admin_actualites.php");
    exit;
}

// Récupérer toutes les actualités
$actualites = $pdo->query("SELECT * FROM actualites ORDER BY ordre ASC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion Actualités - Admin</title>
    
    <!-- =========================
        CSS
		========================= -->

<link rel="stylesheet" href="/assets/css/style.css">
<link rel="stylesheet" href="/assets/css/header.css">
<link rel="stylesheet" href="/assets/css/admin.css">
<link rel="stylesheet" href="/assets/css/admin_responsive.css">
<link rel="stylesheet" href="/assets/css/footer.css">
    
</head>
<body class="admin-page">

<?php include('../templates/header.php'); ?>

<div class="container">
    <h2>Gestion des Actualités</h2>

    <!-- Formulaire -->
    <form class="actualite-form" method="post" enctype="multipart/form-data">
        <input type="text" name="titre" placeholder="Titre de l'actualité" required>
        <input type="number" name="ordre" placeholder="Ordre d'affichage" required>
        <input type="file" name="image" accept="image/*" required>
        <button type="submit" name="add">Ajouter</button>
    </form>

    <!-- Tableau -->
    <h3>Liste des actualités</h3>
    
   <div class="documents-table-wrapper">
        <table class="actualites-table">
            <tr>
                <th>ID</th><th>Titre</th><th>Image</th><th>Ordre</th><th>Actions</th>
            </tr>
            <?php foreach($actualites as $a): ?>
            <tr>
                <td data-label="ID"><?= $a['id'] ?></td>
                <td data-label="Titre"><?= htmlspecialchars($a['titre']) ?></td>
                <td data-label="Image"><img src="../assets/uploads/actualites/<?= htmlspecialchars($a['nom_fichier']) ?>" alt="<?= htmlspecialchars($a['titre']) ?>"></td>
                <td data-label="Ordre"><?= $a['ordre'] ?></td>
                <td data-label="Actions">
                    <a href="admin_actualites.php?delete=<?= $a['id'] ?>" class="delete-link">Supprimer</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>

<?php include('../templates/footer.php'); ?>

<!-- JS global + admin -->
<script src="../assets/js/main.js"></script>
<script src="../assets/js/admin.js"></script>
</body>
</html>
