<?php
session_start();
require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/config/db.php';

$message = '';
$error = '';

if(isset($_GET['token'])){
    $token = trim($_GET['token']);

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email_token = :token LIMIT 1");
    $stmt->execute(['token' => $token]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if(!$user){
        $error = "Token invalide. Veuillez vérifier votre lien.";
    }
    else {
        $now = date('Y-m-d H:i:s');

        if($user['token_expire'] < $now){
            $error = "Le lien a expiré. Veuillez demander un nouveau lien.";
        }
        else {
            $stmt = $pdo->prepare("
                UPDATE users 
                SET email_verified = 1, email_token = NULL, token_expire = NULL
                WHERE id_user = :id_user
            ");

            $stmt->execute(['id_user' => $user['id_user']]);

            $message = "Compte activé avec succès ! Vous pouvez vous connecter.";
        }
    }
}
else {
    $error = "Aucun token fourni.";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Activation - PromoUnité</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link rel="stylesheet" href="assets/css/inscription_login.css">
<link rel="stylesheet" href="assets/css/responsive.css">

</head>

<body class="login-page">

<div class="login-container">

    <h2>Activation compte</h2>

    <?php if($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if($message): ?>
        <div class="message"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <div class="login-links">
        <a class="link-left" href="login.php">Connexion</a>
        
    </div>

</div>

</body>
</html>
