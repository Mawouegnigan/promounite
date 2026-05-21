<?php
session_start();

require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/config/app.php';

// 🔴 Correction ordre session (important)
if(isset($_SESSION['id_user'])){
    header("Location: index.php");
    exit;
}

$error = '';
$identifiant = '';

if($_SERVER['REQUEST_METHOD'] === 'POST'){

    $identifiant = trim($_POST['identifiant']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE id_user = :id_user LIMIT 1");
    $stmt->execute(['id_user' => $identifiant]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if(!$user){
        $error = "Identifiant ou mot de passe incorrect.";
    }
    elseif(!password_verify($password, $user['password'])){
        $error = "Identifiant ou mot de passe incorrect.";
    }
    elseif($user['email_verified'] == 0){
        $error = "Votre compte n'est pas activé. Vérifiez votre email.";
    }
    else {
        $_SESSION['id_user'] = $user['id_user'];
        $_SESSION['nom'] = $user['nom'];
        $_SESSION['prenom'] = $user['prenom'];
        $_SESSION['role'] = $user['role'] ?? '';

        header("Location: index.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Connexion - PromoUnité</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="assets/css/inscription_login.css">
<link rel="stylesheet" href="assets/css/responsive.css">
</head>

<body class="login-page">

<div class="login-container">

<h2>Connexion</h2>

<?php if($error): ?>
<div class="error"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<form method="POST">

<input type="text" name="identifiant"
       value="<?= htmlspecialchars($identifiant) ?>"
       placeholder="Identifiant" required>

<div style="position:relative;">

    <input type="password" name="password"
      placeholder="Mot de passe" required>

<button type="submit">Se connecter</button>

<div class="login-links">
    <a href="mot_identifiant.php">Besoin d'ID ?</a>
    <a href="inscription.php">S'inscrire</a>
</div>

<div class="forgot-password">
    <a href="forgot_password.php">Mot de passe oublié ?</a>
</div>

</form>

</div>

</body>
</html>
