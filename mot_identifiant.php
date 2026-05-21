<?php 
session_start();
require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/config/db.php';

?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Besoin de votre Identifiant - PromoUnité</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="assets/css/inscription_login.css">
<link rel="stylesheet" href="assets/css/responsive.css">
</head>

<body class="login-page">

<div class="request-card">

    <h3>Besoin de votre Identifiant ?</h3>

    <p class="info-text">
        Si vous avez oublié votre identifiant ou si vous n’en avez pas encore reçu, merci de cliquer sur le bouton ci-dessous pour le récupérer via WhatsApp.
    </p>
    
     <p class="info-text">   
       <i> <strong>  Vous serez redirigé vers le Délégué à qui vous aurez à rappeler votre nom et prénoms pour un retour immédiat.  </strong> </i>
    </p>

    <a href="https://wa.me/+22996484648" target="_blank" class="request-button">
        Demander
    </a>

    <div class="bottom-links">
        <a href="login.php" class="link-left">Retour à la connexion</a>
        <a href="inscription.php" class="link-right">S'inscrire</a>
    </div>

</div>
<script src="assets/js/inscription_login.js"></script>
</body>
</html>
