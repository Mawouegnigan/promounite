<?php

session_start();
require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/config/db.php';

$error = '';
$message = '';
$token = $_GET['token'] ?? '';

// CSRF
if(empty($_SESSION['csrf_token'])){
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];

// Vérification token
if(!$token){
    die("Lien invalide.");
}

$stmt = $pdo->prepare("
    SELECT * FROM users 
    WHERE reset_token = ? AND reset_expire > NOW() 
    LIMIT 1
");
$stmt->execute([$token]);
$user = $stmt->fetch();

if(!$user){
    die("Lien invalide ou expiré.");
}

// ======================
// TRAITEMENT FORMULAIRE
// ======================
if($_SERVER['REQUEST_METHOD'] === 'POST'){

    $csrf = $_POST['csrf_token'] ?? '';

    if(!$csrf || !hash_equals($_SESSION['csrf_token'], $csrf)){
        $error = "Erreur de sécurité.";
    }
    else {

        $password = trim($_POST['password'] ?? '');
        $confirm = trim($_POST['confirm_password'] ?? '');

        if(empty($password) || empty($confirm)){
            $error = "Tous les champs sont obligatoires.";
        }
        elseif($password !== $confirm){
            $error = "Les mots de passe ne correspondent pas.";
        }
        elseif(strlen($password) < 6){
            $error = "Mot de passe trop court.";
        }
        elseif(!preg_match('/[A-Z]/', $password) ||
               !preg_match('/[a-z]/', $password) ||
               !preg_match('/\d/', $password)){
            $error = "Mot de passe invalide.";
        }
        else {

            $hash = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare("
                UPDATE users 
                SET password = ?, reset_token = NULL, reset_expire = NULL
                WHERE id_user = ?
            ");
            $stmt->execute([$hash, $user['id_user']]);

            $message = "Mot de passe réinitialisé avec succès.";

            unset($_SESSION['csrf_token']);

            header("refresh:5;url=login.php");
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Nouveau mot de passe - PromoUnité</title>

<link rel="stylesheet" href="assets/css/inscription_login.css">
</head>

<body class="login-page">

<div class="login-container">

<h2>Nouveau mot de passe</h2>

<?php if($error): ?>
<div class="error"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<?php if($message): ?>

<div class="message"><?= htmlspecialchars($message) ?></div>

<div class="links">
<a href="login.php">Connexion immédiate</a>
</div>

<?php else: ?>

<form method="POST">

<input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">

<input type="password" name="password"
placeholder="Nouveau mot de passe" required>

<input type="password" name="confirm_password"
placeholder="Confirmer mot de passe" required>

<button type="submit">Valider</button>

</form>

<?php endif; ?>

</div>

</body>
</html>
