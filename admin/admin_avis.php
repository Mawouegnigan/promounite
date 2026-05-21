<?php
session_start();

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/db.php';

if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'admin') {
    header('Location: /login.php');
    exit();
}


/* =================================================
   🔴 SUPPRESSION COMMENTAIRE
================================================= */
if(isset($_GET['delete'])){

    $id = intval($_GET['delete']);

    $stmt = $pdo->prepare("DELETE FROM comments WHERE id = ?");
    $stmt->execute([$id]);

    header("Location: admin_avis.php");
    exit;
}


/* =================================================
   🔵 RÉCUPÉRATION COMMENTAIRES
================================================= */
$sql = "
    SELECT 
        c.id,
        c.comment,
        c.created_at,
        u.nom,
        u.prenom
    FROM comments c
    JOIN users u ON u.id_user = c.id_user
    ORDER BY c.created_at DESC
";

$stmt = $pdo->query($sql);
$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Avis utilisateurs</title>
   <!-- =========================
     LES HEAD DE LA PAGE
========================= -->
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

    <h2>💬 Avis et Suggestions des utilisateurs</h2>

    <?php if(empty($comments)): ?>
        <p>Aucun commentaire pour le moment.</p>
    <?php else: ?>
<div class="table-avis-wrapper">
        <table class="table-avis">
            <thead>
                <tr>
                    <th>N°</th>
                    <th>Date</th>
                    <th>Utilisateur</th>
                    <th>Commentaire</th>
                    <th>Actions</th>
    			</tr>
            </thead>

            <tbody>
                <?php foreach($comments as $index => $c): ?>
                    <tr>
                        <td data-label="N°"><?= $index + 1 ?></td>

                        <td data-label="Date">
                            <?= date('d/m/Y H:i', strtotime($c['created_at'])) ?>
                        </td>

                        <td data-label="Utilisateur">
                            <?= htmlspecialchars($c['prenom'].' '.$c['nom']) ?>
                        </td>

                        <td data-label="Commentaire">
                            <?= nl2br(htmlspecialchars($c['comment'])) ?>
                        </td>
                        
                         <td data-label="Actions">
                            <a href="admin_avis.php?delete=<?= $c['id'] ?>"
                               onclick="return confirm('Supprimer ce commentaire ?')">
                               Supprimer
                            </a>
						</td>    
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    <?php endif; ?>
</div>
</div>

<?php include('../templates/footer.php'); ?>
<!-- JS global + admin -->
<script src="../assets/js/main.js"></script>
<script src="../assets/js/admin.js"></script>

</body>
</html>
