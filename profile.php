<?php
session_start();

require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/config/db.php';


if(!isset($_SESSION['id_user'])){
    header('Location: login.php');
    exit();
}

$id_user = $_SESSION['id_user'];
$error = '';
$message = '';

// utilisateur
$stmt = $pdo->prepare("SELECT * FROM users WHERE id_user = :id_user");
$stmt->execute(['id_user' => $id_user]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$user){
    $user = [
        'id_user' => '',
        'nom' => '',
        'prenom' => '',
        'categorie' => '',
        'email' => ''
    ];
}

// update
if($_SERVER['REQUEST_METHOD'] === 'POST'){

    $current_password = $_POST['current_password'] ?? '';
    $new_password     = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $email            = trim($_POST['email'] ?? '');

    if(!password_verify($current_password, $user['password'] ?? '')){
        $error = "Mot de passe actuel incorrect.";
    }
    elseif($new_password && $new_password !== $confirm_password){
        $error = "Les nouveaux mots de passe ne correspondent pas.";
    }
    else {

        $fields = ['email' => $email];

        if($new_password){
            $fields['password'] = password_hash($new_password, PASSWORD_DEFAULT);
        }

        $set_sql = implode(', ', array_map(fn($k) => "$k = :$k", array_keys($fields)));
        $stmt = $pdo->prepare("UPDATE users SET $set_sql WHERE id_user = :id_user");

        $fields['id_user'] = $id_user;
        $stmt->execute($fields);

        $message = "Profil mis à jour avec succès !";

        $stmt = $pdo->prepare("SELECT * FROM users WHERE id_user = :id_user");
        $stmt->execute(['id_user' => $id_user]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Profil - PromoUnité</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link rel="stylesheet" href="/assets/css/style.css">
<link rel="stylesheet" href="/assets/css/responsive.css">
</head>

<body>

<?php include('templates/header.php'); ?>

<div class="container">

    <div class="profile-card-site">

        <h2>Mon Profil</h2>

        <?php if($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if($message): ?>
            <div class="message"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <form method="POST">

            <input type="text" value="<?= htmlspecialchars($user['id_user']) ?>" readonly>
            <input type="text" value="<?= htmlspecialchars($user['nom']) ?>" readonly>
            <input type="text" value="<?= htmlspecialchars($user['prenom']) ?>" readonly>
            <input type="text" value="<?= htmlspecialchars($user['categorie']) ?>" readonly>

            <input type="email" name="email"
                   value="<?= htmlspecialchars($user['email']) ?>" required>

            <input type="password" name="current_password" placeholder="Mot de passe actuel" required>

            <input type="password" name="new_password" placeholder="Nouveau mot de passe">

            <input type="password" name="confirm_password" placeholder="Confirmer nouveau mot de passe">

            <button type="submit">Mettre à jour</button>

        </form>

    </div>

</div>

<?php include('templates/footer.php'); ?>

<script src="assets/js/main.js"></script>

</body>
</html>
