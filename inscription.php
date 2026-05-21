<?php
session_start();

require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/config/app.php';

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$message = '';
$error = '';

if(empty($_SESSION['csrf_token'])){
    $_SESSION['csrf_token'] = bin2hex(openssl_random_pseudo_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];

$temp_id_user = $_SESSION['id_user_temp'] ?? '';

if($_SERVER['REQUEST_METHOD'] === 'POST'){

    if(!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']){
        die("Requête invalide (CSRF).");
    }

    $identifiant = $temp_id_user ?: trim($_POST['identifiant']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    if($password !== $confirm){
        $error = "Les mots de passe ne correspondent pas.";
    } else {

        $stmt = $pdo->prepare("SELECT * FROM users WHERE id_user = :id_user LIMIT 1");
        $stmt->execute(['id_user'=>$identifiant]);
        $userExists = $stmt->fetch(PDO::FETCH_ASSOC);

        if(!$userExists){
            $error = "Identifiant invalide. Veuillez contacter l'administrateur.";
        } else {

            $hash = password_hash($password, PASSWORD_DEFAULT);

            $email_token = bin2hex(openssl_random_pseudo_bytes(32));
            $token_expire = date('Y-m-d H:i:s', strtotime('+30 minutes'));

            $stmt = $pdo->prepare("
                UPDATE users 
                SET email = :email, password = :password,
                    email_verified = 0, email_token = :token, token_expire = :expire
                WHERE id_user = :id_user
            ");

            $stmt->execute([
                'id_user' => $identifiant,
                'email' => $email,
                'password' => $hash,
                'token' => $email_token,
                'expire' => $token_expire
            ]);

            try {
                $mail = new PHPMailer(true);
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'promotionunite@gmail.com';
                $mail->Password = 'mmcqycserqeeqdck';
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;

                $mail->setFrom('promotionunite@gmail.com', 'PromoUnite');
                $mail->addAddress($email, $userExists['prenom'].' '.$userExists['nom']);

                $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
                $host = $_SERVER['HTTP_HOST'];
                $path = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                $verify_link = $protocol . $host . $path . "/verify_email.php?token=" . $email_token;

                $mail->isHTML(true);
                $mail->Subject = 'Confirmez votre compte PromoUnite';
                $mail->Body = "Bonjour {$userExists['prenom']},<br><br>
                    Cliquez sur le lien ci-dessous :<br>
                    <a href='{$verify_link}'>Activer mon compte</a><br><br>
                    Expire dans 15 minutes.";

                $mail->send();
                $message = "Inscription réussie ! Vérifiez votre email.";

            } catch (Exception $e) {
                $error = "Impossible d'envoyer l'email.";
            }

            unset($_SESSION['id_user_temp']);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Inscription - PromoUnité</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="assets/css/inscription_login.css">
<link rel="stylesheet" href="assets/css/responsive.css">
</head>

<body class="login-page">

<div class="login-container">

<h2>Inscription</h2>

<?php if($error): ?>
<div class="error"><?= $error ?></div>
<?php endif; ?>

<?php if($message): ?>
<div class="success"><?= $message ?></div>
<?php endif; ?>

<form method="POST" id="registerForm">

<input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">

<input type="text" name="identifiant" id="identifiant"  placeholder="Identifiant"
value="<?= htmlspecialchars($temp_id_user) ?>"
<?= $temp_id_user ? 'readonly' : '' ?> required>

<input type="text" name="nom" id="nom" placeholder="Nom"
value="<?= htmlspecialchars($userExists['nom'] ?? '') ?>" readonly>

<input type="text" name="prenom" id="prenom" placeholder="Prénom"
value="<?= htmlspecialchars($userExists['prenom'] ?? '') ?>" readonly>

<input type="text" name="categorie" id="categorie" placeholder="Catégorie"
value="<?= htmlspecialchars($userExists['categorie'] ?? '') ?>" readonly>

<input type="email" name="email" placeholder="Email" required>

<input type="password" name="password" placeholder="Mot de passe" required>

<input type="password" name="confirm_password" placeholder="Confirmer mot de passe" required>

<button type="submit"><?= $temp_id_user ? 'Enregistrer mot de passe' : "S'inscrire" ?></button>

<div class="login-links">
<a href="mot_identifiant.php">Besoin d'ID ?</a>
<a href="login.php">Retour connexion</a>
</div>

</form>

</div>

<script src="assets/js/inscription_login.js"></script>

</body>
</html>
