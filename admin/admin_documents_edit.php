<?php
session_start();

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/db.php';

if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'admin') {
    header('Location: /login.php');
    exit();
}





$upload_dir = '../assets/uploads/documents/';
if(!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

$table = $_GET['table'] ?? null;
$id = intval($_GET['id'] ?? 0);
if(!$table || !$id) die("Document invalide");

$colFile = 'fichier';

switch(strtolower($table)){
    case 'cours':
    case 'td':
    case 'evaluations':
    case 'normes':
    case 'carriere':
        break;
    default: die("Table inconnue");
}

$stmt = $pdo->prepare("SELECT * FROM $table WHERE id=?");
$stmt->execute([$id]);
$doc = $stmt->fetch();
if(!$doc) die("Document introuvable");

$matieres = [];
if(in_array(strtolower($table), ['cours','td','evaluations'])){
    $matieres = $pdo->query("SELECT id, nom FROM matieres ORDER BY nom")->fetchAll(PDO::FETCH_ASSOC);
}

if(isset($_POST['update'])){
    $titre = htmlspecialchars($_POST['titre']);
    $matiere_id = $_POST['matiere_id'] ?? null;
    $annee = $_POST['annee'] ?? null;
    $type_eval = $_POST['type_eval'] ?? null;
    $type_norme = $_POST['type_norme'] ?? null;

    $filename = $doc[$colFile];

    if(isset($_FILES['fichier']) && $_FILES['fichier']['error']===0){
        $newFile = time().'_'.basename($_FILES['fichier']['name']);
        $target = $upload_dir.$newFile;

        if(move_uploaded_file($_FILES['fichier']['tmp_name'],$target)){
            if(file_exists($upload_dir.$filename)) unlink($upload_dir.$filename);
            $filename = $newFile;
        }
    }

    switch(strtolower($table)){
        case 'cours':
        case 'td':
            $stmt = $pdo->prepare("UPDATE $table SET titre=?, matiere_id=?, annee=?, fichier=? WHERE id=?");
            $stmt->execute([$titre, $matiere_id, $annee, $filename, $id]);
            break;

        case 'evaluations':
            $stmt = $pdo->prepare("UPDATE evaluations SET titre=?, matiere_id=?, type_eval=?, fichier=? WHERE id=?");
            $stmt->execute([$titre, $matiere_id, $type_eval, $filename, $id]);
            break;

        case 'normes':
            $stmt = $pdo->prepare("UPDATE normes SET titre=?, type_norme=?, fichier=? WHERE id=?");
            $stmt->execute([$titre, $type_norme, $filename, $id]);
            break;

        case 'carriere':
            $stmt = $pdo->prepare("UPDATE carriere SET titre=?, fichier=? WHERE id=?");
            $stmt->execute([$titre, $filename, $id]);
            break;
    }

    header("Location: admin_documents.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Modifier Document</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/header.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="../assets/css/admin_responsive.css">
    <link rel="stylesheet" href="/assets/css/footer.css">

</head>

<body class="admin-page"  data-table="<?= strtolower($table) ?>">
<?php include('../templates/header.php'); ?>

<div class="container">
<h2 class="title">Modifier Document</h2>

<form method="post" enctype="multipart/form-data" class="card">

<input type="text" name="titre" value="<?= htmlspecialchars($doc['titre']) ?>" required>

<div id="bloc-matiere">
<?php if(in_array(strtolower($table), ['cours','td','evaluations'])): ?>

    <label>Matière</label>
    <select name="matiere_id" required>
        <option value="">-- Choisir --</option>
        <?php foreach($matieres as $m): ?>
            <option value="<?= $m['id'] ?>" <?= ($doc['matiere_id']==$m['id'])?'selected':'' ?>>
                <?= htmlspecialchars($m['nom']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <?php if(in_array(strtolower($table), ['cours','td'])): ?>
        <label>Année</label>
        <select name="annee">
            <option value="1" <?= ($doc['annee']==1)?'selected':'' ?>>Année 1</option>
            <option value="2" <?= ($doc['annee']==2)?'selected':'' ?>>Année 2</option>
        </select>
    <?php endif; ?>

    <?php if(strtolower($table)=='evaluations'): ?>
        <label>Type</label>
        <select name="type_eval">
            <option value="Interro" <?= ($doc['type_eval']=='Interro')?'selected':'' ?>>Interro</option>
            <option value="Devoir" <?= ($doc['type_eval']=='Devoir')?'selected':'' ?>>Devoir</option>
        </select>
    <?php endif; ?>

<?php endif; ?>
</div>

<div id="bloc-norme">
<?php if(strtolower($table)=='normes'): ?>
    <label>Type de norme</label>
    <select name="type_norme">
        <option value="Loi" <?= ($doc['type_norme']=='Loi')?'selected':'' ?>>Loi</option>
        <option value="Décret" <?= ($doc['type_norme']=='Décret')?'selected':'' ?>>Décret</option>
        <option value="Arrêté" <?= ($doc['type_norme']=='Arrêté')?'selected':'' ?>>Arrêté</option>
        <option value="Constitution" <?= ($doc['type_norme']=='Constitution')?'selected':'' ?>>Constitution</option>
        <option value="Circulaire" <?= ($doc['type_norme']=='Circulaire')?'selected':'' ?>>Circulaire</option>
    </select>
<?php endif; ?>
</div>

<p>Fichier actuel : 
<a href="<?= $upload_dir.$doc[$colFile] ?>" target="_blank">Voir</a></p>

<input type="file" name="fichier">

<button type="submit" name="update">Mettre à jour</button>

</form>
</div>

<?php include('../templates/footer.php'); ?>
<!-- JS global + admin -->
<script src="../assets/js/main.js"></script>
<script src="../assets/js/admin.js"></script>

</body>
</html>
