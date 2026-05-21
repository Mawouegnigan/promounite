<?php
session_start();

require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/config/db.php';

require __DIR__ . '/PHPMailer/src/Exception.php';
require __DIR__ . '/PHPMailer/src/PHPMailer.php';
require __DIR__ . '/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$message = '';
$error = '';

// ==========================
// CSRF TOKEN
// ==========================
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];

// ==========================
// FORM HANDLING
// ==========================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $csrf = $_POST['csrf_token'] ?? '';

    if (!$csrf || !hash_equals($_SESSION['csrf_token'], $csrf)) {
        $error = "Erreur de sécurité. Veuillez réessayer.";
    } else {

        $identifiant = trim($_POST['identifiant'] ?? '');

        if (empty($identifiant)) {
            $error = "Veuillez entrer votre identifiant ou email.";
        } else {

            // ==========================
            // FIND USER
            // ==========================
            $stmt = $pdo->prepare("
                SELECT * FROM users
                WHERE id_user = ? OR email = ?
                LIMIT 1
            ");

            $stmt->execute([$identifiant, $identifiant]);
            $user = $stmt->fetch();

            // Message neutre (sécurité)
            $generic_msg = "Si votre identifiant ou email existe, un lien a été envoyé.";

            if ($user) {

                // ==========================
                // TOKEN + EXPIRATION (15 MIN)
                // ==========================
                $token = bin2hex(random_bytes(32));
                $expire = date('Y-m-d H:i:s', strtotime('+15 minutes'));

                $stmt = $pdo->prepare("
                    UPDATE users
                    SET reset_token = ?, reset_expire = ?
                    WHERE id_user = ?
                ");

                $stmt->execute([$token, $expire, $user['id_user']]);

                $reset_link = "https://promounite-tpb.org/reset_password.php?token=$token";

                try {

                    $mail = new PHPMailer(true);

                    // ==========================
                    // SMTP GMAIL PRODUCTION
                    // ==========================
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;

                    // 🔥 À REMPLACER
                    $mail->Username = 'promotionunite@gmail.com';
                    $mail->Password = 'mmcqycserqeeqdck';

                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = 587;

                    $mail->setFrom('promotionunite@gmail.com', 'PromoUnite'); 
                    $mail->addAddress($user['email'], $user['prenom'].' '.$user['nom']);

                    $mail->isHTML(true);
                    $mail->Subject = 'Reinitialisation mot de passe PromoUnite'; 

                    $mail->Body = "
                        Bonjour {$user['prenom']},<br><br>

                        Cliquez ici pour réinitialiser votre mot de passe :<br>
                        <a href='$reset_link'>Réinitialiser mon mot de passe</a><br><br>

                        ⚠️ Ce lien expire dans 15 minutes.
                    ";

                    // ❌ PAS DE DEBUG EN PROD
                    $mail->SMTPDebug = 0;

                    $mail->send();

                } catch (\Throwable $e) {
                    // log uniquement
                    error_log("MAIL ERROR: " . $e->getMessage());
                }

            }

            $message = $generic_msg;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mot de passe oublié</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/inscription_login.css">
</head>

<body class="login-page">

<div class="login-container">

    <h2>Mot de passe oublié</h2>

    <?php if ($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if ($message): ?>
        <div class="message"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form method="POST">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">

        <input type="text" name="identifiant" placeholder="Identifiant ou Email" required>

        <button type="submit">Réinitialiser</button>
    </form>

    <div class="links">
        <a href="login.php">Retour à la connexion</a>
    </div>

</div>

</body>
</html>
