<?php
session_start();

require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/config/app.php';


if(!isset($_SESSION['id_user'])){
    header("Location: login.php");
    exit;
}

include __DIR__ . '/templates/header.php';

$type  = $_GET['type'] ?? 'cours';
$annee = $_GET['annee'] ?? null;
$sub   = $_GET['sub'] ?? null;
$cat   = $_GET['cat'] ?? null;

$docs = [];
$sql = "";
$params = [];

switch($type){

    case 'cours':
        $sql = "SELECT c.id, c.titre, c.fichier, c.annee, m.nom AS matiere
                FROM cours c
                LEFT JOIN matieres m ON c.matiere_id = m.id
                WHERE 1=1";

        if($annee){
            $sql .= " AND c.annee = ?";
            $params[] = $annee;
        }

        $sql .= " ORDER BY c.id DESC";
        break;

    case 'td':
        $sql = "SELECT t.id, t.titre, t.fichier, t.annee, m.nom AS matiere
                FROM td t
                LEFT JOIN matieres m ON t.matiere_id = m.id
                WHERE 1=1";

        if($annee){
            $sql .= " AND t.annee = ?";
            $params[] = $annee;
        }

        $sql .= " ORDER BY t.id DESC";
        break;

    case 'eval':
        $sql = "SELECT e.id, e.titre, e.fichier, e.type_eval, e.annee, m.nom AS matiere
                FROM evaluations e
                LEFT JOIN matieres m ON e.matiere_id = m.id
                WHERE 1=1";

        if($annee){
            $sql .= " AND e.annee = ?";
            $params[] = $annee;
        }

        if($sub){
            $sql .= " AND e.type_eval = ?";
            $params[] = ucfirst($sub);
        }

        $sql .= " ORDER BY e.id DESC";
        break;

    case 'normes':
        $sql = "SELECT id, titre, fichier, type_norme
                FROM normes
                WHERE 1=1";

        if($cat){
            $sql .= " AND type_norme = ?";
            $params[] = ucfirst($cat);
        }

        $sql .= " ORDER BY id DESC";
        break;

    case 'carriere':
        $sql = "SELECT id, titre, fichier
                FROM carriere
                ORDER BY id DESC";
        break;
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$docs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<div class="container">

<h2>
<?php
echo match($type){
    'cours' => '📘 Cours',
    'td' => '📗 TD',
    'eval' => '📝 Évaluations',
    'normes' => '⚖️ Normes',
    'carriere' => '🚀 Carrière',
};
?>
</h2>

<?php if(empty($docs)): ?>
    <div class="empty-state">Aucun document</div>
<?php else: ?>

<div class="cours-cards">

<?php foreach($docs as $d): ?>

<div class="cours-card">

    <h4><?= htmlspecialchars($d['titre']) ?></h4>

    <span class="badge"><?= ucfirst($type) ?></span>

    <?php if(!empty($d['matiere'])): ?>
        <span class="badge-matiere"><?= htmlspecialchars($d['matiere']) ?></span>
    <?php endif; ?>

    <?php if(!empty($d['type_eval'])): ?>
        <span class="badge"><?= htmlspecialchars($d['type_eval']) ?></span>
    <?php endif; ?>

    <?php if(!empty($d['type_norme'])): ?>
        <span class="badge"><?= htmlspecialchars($d['type_norme']) ?></span>
    <?php endif; ?>

    <div class="doc-actions">

        <a class="btn-view"
           target="_blank"
           href="assets/uploads/documents/<?= rawurlencode($d['fichier']) ?>">
            Voir
        </a>

        <a class="btn-download"
           href="assets/uploads/documents/<?= rawurlencode($d['fichier']) ?>"
           download>
            Télécharger
        </a>

    </div>

</div>

<?php endforeach; ?>

</div>

<?php endif; ?>

</div>

<?php include __DIR__ . '/templates/footer.php'; ?>
