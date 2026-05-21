<?php
session_start();

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/db.php';

if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'admin') {
    header('Location: /login.php');
    exit();
}


// Ajouter annonce
if(isset($_POST['add'])) {
    $titre = $_POST['titre'];
    $contenu = $_POST['contenu'];
    $ordre = $_POST['ordre'];

    $stmt = $pdo->prepare("INSERT INTO annonces (titre, contenu, ordre, statut) VALUES (?, ?, ?, 'actif')");
    $stmt->execute([$titre, $contenu, $ordre]);

    header("Location: admin_annonces.php");
    exit;
}

// Supprimer annonce
if(isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $pdo->prepare("DELETE FROM annonces WHERE id=?");
    $stmt->execute([$id]);

    header("Location: admin_annonces.php");
    exit;
}

$annonces = $pdo->query("SELECT * FROM annonces ORDER BY ordre ASC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Gestion Annonces</title>

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

    <h2>Gestion des Annonces</h2>

    <!-- ========================= -->
    <!-- FORMULAIRE AJOUT -->
    <!-- ========================= -->
    <form method="post" class="card-form">

        <input type="text" name="titre" placeholder="Titre (Annonce X)" required>

        <textarea name="contenu" placeholder="Contenu de l'annonce" required></textarea>

        <input type="number" name="ordre" placeholder="Ordre d'affichage" required>

        <button type="submit" name="add">Ajouter</button>

    </form>

    <h3>Liste des annonces</h3>

    <div class="documents-table-wrapper">
        <table>
            <tr>
                <th>ID</th>
                <th>Titre</th>
                <th>Contenu</th>
                <th>Ordre</th>
                <th>Actions</th>
            </tr>

            <?php foreach($annonces as $a): ?>
            <tr>
                <td data-label="ID"><?= $a['id'] ?></td>
                <td data-label="Titre"><?= htmlspecialchars($a['titre']) ?></td>
                <td data-label="Contenu"><?= htmlspecialchars($a['contenu']) ?></td>
                <td data-label="Ordre"><?= $a['ordre'] ?></td>
                <td data-label="Actions">
                    <a href="?delete=<?= $a['id'] ?>">Supprimer</a>
                </td>
            </tr>
            <?php endforeach; ?>

        </table>
    </div>

</div>

<?php include('../templates/footer.php'); ?>

<script src="../assets/js/main.js"></script>
<script src="../assets/js/admin.js"></script>

</body>
</html>
