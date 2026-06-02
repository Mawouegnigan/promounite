<?php
require_once __DIR__ . '/config/db.php';

$search = $_GET['q'] ?? '';

$sql = "SELECT c.titre, c.fichier, m.nom AS matiere
        FROM cours c
        LEFT JOIN matieres m ON c.matiere_id = m.id
        WHERE c.titre LIKE ? OR m.nom LIKE ?
        ORDER BY c.id DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute(["%$search%", "%$search%"]);
$results = $stmt->fetchAll();

foreach ($results as $r) {
    echo "<div style='padding:10px;border-bottom:1px solid #ddd;'>
            <b>{$r['titre']}</b><br>
            Matière: {$r['matiere']}
          </div>";
}
?>
