<?php
session_start();
header('Content-Type: application/json');
require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/config/db.php';


$id = $_GET['id'] ?? '';
$id = trim($id);

if(!$id){
    echo json_encode(['success'=>false, 'message'=>'ID manquant']);
    exit;
}

// ✅ CORRECTION ICI → users au lieu de users_base
$stmt = $pdo->prepare("SELECT nom, prenom, categorie FROM users WHERE id_user = :id_user");
$stmt->execute(['id_user' => $id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if($user){
    echo json_encode([
        'success' => true,
        'nom' => $user['nom'],
        'prenom' => $user['prenom'],
        'categorie' => $user['categorie']
    ]);
} else {
    echo json_encode(['success'=>false, 'message'=>'Utilisateur non trouvé']);
}
