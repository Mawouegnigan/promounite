<?php
session_start();
require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/config/db.php';

if (!isset($_SESSION['id_user'])) {
    header('Location: login.php');
    exit();
}

include __DIR__ . '/templates/header.php';

/* =========================
   CARROUSEL
========================= */
$stmt = $pdo->query("
    SELECT nom_fichier, titre
    FROM actualites
    WHERE statut='actif'
    ORDER BY ordre ASC
");

$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

$actualites = [];

foreach ($rows as $row) {
    if (!empty($row['nom_fichier'])) {
        $filePath = __DIR__ . '/assets/uploads/actualites/' . $row['nom_fichier'];

        if (file_exists($filePath)) {
            $actualites[] = $row;
        }
    }
}

/* =========================
   STATS
========================= */






$nb_docs = $pdo->query("
    SELECT
        (SELECT COUNT(*) FROM cours) +
        (SELECT COUNT(*) FROM td) +
        (SELECT COUNT(*) FROM evaluations)
")->fetchColumn();

/* téléchargements */
$nb_downloads = $pdo->query("
    SELECT SUM(telechargements) FROM documents
")->fetchColumn() ?? 0;

/* utilisateurs */
$nb_users = $pdo->query("
    SELECT COUNT(*) FROM users
")->fetchColumn();

/* consultations */
$nb_views = $pdo->query("
    SELECT SUM(vues) FROM documents
")->fetchColumn() ?? 0;








?>

<!DOCTYPE html>
<html lang="fr" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta http-equiv="Content-Security-Policy" content="
        default-src 'self';
        img-src 'self' data:;
        script-src 'self';
        style-src 'self' 'unsafe-inline';
    ">

    <title>PromoUnité - Cours, TD, Évaluations & Normes</title>

    <meta name="description" content="PromoUnité est une plateforme pédagogique permettant d'accéder aux cours, TD et évaluations.">
    <meta name="robots" content="index, follow">

    <meta property="og:title" content="PromoUnité - Plateforme pédagogique">
    <meta property="og:description" content="Accès aux cours, TD et évaluations.">
    <meta property="og:image" content="https://promounite-tpb.org/assets/images/logo.png">
    <meta property="og:type" content="website">

    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/header.css">
    <link rel="stylesheet" href="/assets/css/index.css">
    <link rel="stylesheet" href="/assets/css/footer.css">
</head>

<body>

<!-- ===== HOME HEADER ===== -->
<div class="home-header">
    <h3>
        Bienvenue
        <span id="username">
            <?= htmlspecialchars($_SESSION['prenom'] . ' ' . $_SESSION['nom']) ?>
        </span>
    </h3>
</div>

<!-- ===== CARROUSEL ===== -->
<div class="carousel-container">

    <div class="carousel-slides">

        <?php if (count($actualites) > 0): ?>

            <?php foreach ($actualites as $index => $row): ?>

                <div class="carousel-slide<?= $index === 0 ? ' active' : '' ?>">
                    <img src="/assets/uploads/actualites/<?= htmlspecialchars($row['nom_fichier']) ?>"
                         alt="<?= htmlspecialchars($row['titre']) ?>">

                    <div class="carousel-caption">
                        <?= htmlspecialchars($row['titre']) ?>
                    </div>
                </div>

            <?php endforeach; ?>

        <?php else: ?>

            <div class="carousel-slide active">
                <img src="/assets/images/barniere_connexion.png" alt="Aucune actualité">
            </div>

        <?php endif; ?>

    </div>

    <button class="carousel-prev">&#10094;</button>
    <button class="carousel-next">&#10095;</button>

    <div class="carousel-indicators">
        <?php foreach ($actualites as $i => $row): ?>
            <span class="carousel-indicator<?= $i === 0 ? ' active' : '' ?>"
                  data-slide="<?= $i ?>"></span>
        <?php endforeach; ?>
    </div>

</div>

<!-- ===== ANNONCES ===== -->
<div class="annonces-container">
    <div class="annonces-wrapper">
        <?php
        $stmt = $pdo->query("SELECT titre, contenu FROM annonces WHERE statut='actif'");
        while ($row = $stmt->fetch()) {
            echo "<span class='annonce'>📢 "
                . htmlspecialchars($row['titre'])
                . " : "
                . htmlspecialchars($row['contenu'])
                . "</span>";
        }
        ?>
    </div>
</div>

<!-- ===== MESSAGE ===== -->
<div class="message-box text-message">
    <p>
        🤝 L’unité et la compréhension sont notre force. <br>
	 Au plaisir de vous retrouver pour continuer nos beaux projets ensemble; <br>
	 de relever ensemble les défis de profession, de convivialité et de solidarité; <br>
    </p>
</div>

<!-- ===== MAIN ===== -->
<div class="container main-content">

    <?php if (isset($_GET['comment']) && $_GET['comment'] === 'success'): ?>
        <div class="success-message">
            👍 Merci pour votre commentaire !
        </div>
    <?php endif; ?>

    <form id="commentForm" action="submit_comment.php" method="POST">
        <input type="text" name="commentaire" placeholder="Votre commentaire..." required>
        <button type="submit">Envoyer</button>
    </form>

    <!-- ===== STATS ===== -->
    <div class="stats-box">
        <div class="stat-card">📄 Documents : <?= $nb_docs ?></div>
        <div class="stat-card">⬇️ Téléchargements : <?= $nb_downloads ?></div>
        <div class="stat-card">👥 Utilisateurs : <?= $nb_users ?></div>
        <div class="stat-card">👁️ Consultations : <?= $nb_views ?></div>
    </div>

</div>

<script src="/assets/js/index.js"></script>

<script>
setTimeout(() => {
    window.history.replaceState({}, document.title, "index.php");
}, 3000);
</script>

<?php include __DIR__ . '/templates/footer.php'; ?>

</body>
</html>
