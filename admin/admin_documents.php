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

/* =======================
   TABLE MAP (IMPORTANT)
======================= */
$tableMap = [
    'Cours' => 'cours',
    'TD' => 'td',
    'Evaluations' => 'evaluations',
    'Normes' => 'normes',
    'Carrière' => 'carriere'
];

$allowed_tables = array_values($tableMap);

/* =======================
   SUPPRESSION DOCUMENT
======================= */
if(isset($_GET['delete'])){

    $table = $_GET['table'] ?? '';
    $id = intval($_GET['id'] ?? 0);

    if(!in_array($table, $allowed_tables) || $id <= 0){
        die("Table invalide.");
    }

    $stmt = $pdo->prepare("SELECT fichier FROM $table WHERE id=?");
    $stmt->execute([$id]);
    $file = $stmt->fetch();

    if($file && file_exists($upload_dir.$file['fichier'])){
        unlink($upload_dir.$file['fichier']);
    }

    $stmt = $pdo->prepare("DELETE FROM $table WHERE id=?");
    $stmt->execute([$id]);

    header("Location: admin_documents.php");
    exit;
}

/* =======================
   AJOUT DOCUMENT
======================= */
if(isset($_POST['add'])){

    $titre = trim($_POST['titre']);
    $type = $_POST['type'];

    $matiere_id = $_POST['matiere_id'] ?? null;
    $annee = $_POST['annee'] ?? null;
    $type_norme = $_POST['type_norme'] ?? null;

    if(isset($_FILES['fichier']) && $_FILES['fichier']['error'] === 0){

        $filename = time().'_'.basename($_FILES['fichier']['name']);
        $target = $upload_dir.$filename;

        if(move_uploaded_file($_FILES['fichier']['tmp_name'],$target)){

            switch($type){

                case 'Cours':
                    $stmt = $pdo->prepare("INSERT INTO cours (titre, matiere_id, annee, fichier) VALUES (?,?,?,?)");
                    $stmt->execute([$titre,$matiere_id,$annee,$filename]);
                    break;

                case 'TD':
                    $stmt = $pdo->prepare("INSERT INTO td (titre, matiere_id, annee, fichier) VALUES (?,?,?,?)");
                    $stmt->execute([$titre,$matiere_id,$annee,$filename]);
                    break;

                case 'Interro':
                case 'Devoir':
                    $stmt = $pdo->prepare("INSERT INTO evaluations (titre, matiere_id, type_eval, annee, fichier) VALUES (?,?,?,?,?)");
                    $stmt->execute([$titre,$matiere_id,$type,$annee,$filename]);
                    break;

                case 'Normes':
                    $stmt = $pdo->prepare("INSERT INTO normes (titre, type_norme, fichier) VALUES (?,?,?)");
                    $stmt->execute([$titre,$type_norme,$filename]);
                    break;

                case 'Carrière':
                    $stmt = $pdo->prepare("INSERT INTO carriere (titre, fichier) VALUES (?,?)");
                    $stmt->execute([$titre,$filename]);
                    break;
            }

            header("Location: admin_documents.php");
            exit;
        }
    }
}

/* =======================
   MATIERES
======================= */
$matieres = $pdo->query("SELECT id, nom FROM matieres ORDER BY nom ASC")->fetchAll();

/* =======================
   FETCH DOCS
======================= */
function fetchDocs($pdo, $table, $type_label){

    $rows = [];

    if(in_array($table,['cours','td','evaluations'])){

        $sql = "SELECT d.id, d.titre, m.nom AS matiere, d.annee, d.fichier, '$type_label' AS type_doc
                FROM $table d
                LEFT JOIN matieres m ON d.matiere_id = m.id
                ORDER BY d.id DESC";

        foreach($pdo->query($sql) as $row){
            $rows[] = $row;
        }

    } elseif($table === 'normes'){

        foreach($pdo->query("SELECT id, titre, type_norme, fichier, 'Normes' AS type_doc FROM normes ORDER BY id DESC") as $row){
            $rows[] = $row;
        }

    } elseif($table === 'carriere'){

        foreach($pdo->query("SELECT id, titre, fichier, 'Carrière' AS type_doc FROM carriere ORDER BY id DESC") as $row){
            $rows[] = $row;
        }
    }

    return $rows;
}

$allDocs = array_merge(
    fetchDocs($pdo,'cours','Cours'),
    fetchDocs($pdo,'td','TD'),
    fetchDocs($pdo,'evaluations','Evaluations'),
    fetchDocs($pdo,'normes','Normes'),
    fetchDocs($pdo,'carriere','Carrière')
);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Admin Documents</title>
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

<h2 style="text-align:center;">Gestion des Documents</h2>

<form method="post" enctype="multipart/form-data" class="card card-form">

    <input type="text" name="titre" placeholder="Titre du document" required>

    <select name="type" id="type" onchange="toggleFields()" required>
        <option value="">-- Choisir --</option>
        <option value="Cours">Cours</option>
        <option value="TD">TD</option>
        <option value="Interro">Interro</option>
        <option value="Devoir">Devoir</option>
        <option value="Normes">Normes</option>
        <option value="Carrière">Carrière</option>
    </select>

    <div id="bloc-matiere" style="display:none;">
        <select name="matiere_id">
            <option value="">Matière</option>
            <?php foreach($matieres as $m): ?>
                <option value="<?= $m['id'] ?>"><?= htmlspecialchars($m['nom']) ?></option>
            <?php endforeach; ?>
        </select>

        <select name="annee">
            <option value="">Année</option>
            <option value="1">Année 1</option>
            <option value="2">Année 2</option>
        </select>
    </div>

    <div id="bloc-norme" style="display:none;">
        <select name="type_norme">
            <option>Loi</option>
            <option>Décret</option>
            <option>Arrêté</option>
            <option>Constitution</option>
            <option>Circulaire</option>
        </select>
    </div>

    <input type="file" name="fichier" required>

    <button type="submit" name="add">Ajouter</button>
</form>

<h3 style="text-align:center;">Documents existants</h3>
<div class="table-scroll">
            <table class="documents-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Titre</th>
                    <th>Type</th>
                    <th>Détail</th>
                    <th>Année</th>
                    <th>Actions</th>
                </tr>
            </thead>
</div>
            <tbody>
<?php foreach($allDocs as $doc): ?>

<?php $table = $tableMap[$doc['type_doc']] ?? null; ?>

<tr>
<td><?= $doc['id'] ?></td>
<td><?= htmlspecialchars($doc['titre']) ?></td>
<td><?= $doc['type_doc'] ?></td>
<td><?= $doc['matiere'] ?? $doc['type_norme'] ?? '-' ?></td>
<td><?= $doc['annee'] ?? '-' ?></td>

<td>

<!-- VOIR -->
<a target="_blank"
   href="<?= $upload_dir.$doc['fichier'] ?>">👁 Voir</a>

<!-- MODIFIER -->
<a href="admin_documents_edit.php?table=<?= $table ?>&id=<?= $doc['id'] ?>">✏ Modifier</a>

<!-- SUPPRIMER -->
<a href="?delete=1&table=<?= $table ?>&id=<?= $doc['id'] ?>"
   onclick="return confirm('Supprimer ?')">🗑 Supprimer</a>

</td>
</tr>

<?php endforeach; ?>
</tbody>
</table>

</div>

<?php include('../templates/footer.php'); ?>

<script>
function toggleFields(){
    const type = document.getElementById('type').value;

    document.getElementById('bloc-matiere').style.display =
        ['Cours','TD','Interro','Devoir'].includes(type) ? 'block' : 'none';

    document.getElementById('bloc-norme').style.display =
        (type === 'Normes') ? 'block' : 'none';
}
</script>

</body>
</html>
