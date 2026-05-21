<?php
session_start();

require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/config/db.php';

// Optionnel : protéger la page si ton site est 100% privé
if (!isset($_SESSION['id_user'])) {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mentions légales - PromoUnité</title>

    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
    <link rel="stylesheet" href="assets/css/mentions.css">
</head>

<body>

<?php include __DIR__ . '/templates/header.php'; ?>

<main class="container">

    <h1>Mentions légales</h1>

    <h2>1. Éditeur du site</h2>
    <p>
        PromoUnité est une plateforme éducative développée dans le cadre d’une formation en développement full stack.
    </p>

    <p>
        Responsable : [Ton nom]<br>
        Statut : Développeur en formation<br>
        Contact : [Ton email]
    </p>

    <h2>2. Hébergement</h2>
    <p>
        Site hébergé sur un serveur VPS sous Ubuntu avec Apache2, PHP et MariaDB.
    </p>

    <h2>3. Propriété intellectuelle</h2>
    <p>
        Tous les contenus (codes, documents, interfaces, ressources pédagogiques) sont protégés.
        Toute reproduction sans autorisation est interdite.
    </p>

    <h2>4. Données personnelles</h2>
    <p>
        Les données sont utilisées uniquement pour le fonctionnement de la plateforme 
        (authentification et accès aux contenus). Aucune donnée n’est vendue ou partagée.
    </p>

    <h2>5. Accès au site</h2>
    <p>
        L’accès est réservé aux utilisateurs autorisés et connectés.
    </p>

    <h2>6. Remerciements</h2>
    <p>
        Ce projet est issu d’un apprentissage progressif en développement web full stack.
    </p>

    <p>
        Je remercie mes aînés du domaine pour leurs conseils et orientations techniques, 
        ainsi que le délégué de la promotion pour son accompagnement et sa coordination.
    </p>

    <h2>7. Responsabilité</h2>
    <p>
        L’éditeur ne peut être tenu responsable des interruptions ou erreurs techniques du service.
    </p>

    <h2>8. Modification</h2>
    <p>
        Les présentes mentions peuvent être modifiées à tout moment.
    </p>

    <hr>

</main>

<?php include __DIR__ . '/templates/footer.php'; ?>

</body>
</html>
