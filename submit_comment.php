<?php

session_start();
require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/config/db.php';


if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $comment = trim($_POST['commentaire']);
    $id_user = $_SESSION['id_user'];

    if (!empty($comment)) {
        $stmt = $pdo->prepare("
            INSERT INTO comments (id_user, comment, created_at)
            VALUES (?, ?, NOW())
        ");
        $stmt->execute([$id_user, $comment]);
    }
}

header("Location: index.php?comment=success");
exit;
