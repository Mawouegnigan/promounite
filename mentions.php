<?php
session_start();

require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/config/db.php';

// Protection (optionnelle)
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

    <h2>1. Présentation du site</h2>
    <p>
        PromoUnité est une plateforme collaborative dédiée aux membres de la Promotion 2024 du Trésor Public du Bénin,
        permettant le partage, la centralisation et la sauvegarde de documents pédagogiques et administratifs.
    </p>

    <h2>2. Administration du site</h2>
    <p>
        La plateforme est gérée par les membres de la promotion, dans un esprit d’entraide,
        de collaboration et de facilitation de l’accès aux ressources communes.
    </p>

    <p>
        Responsables :
        <ol>
            <li>GBENAKPON STANISLAS AGBANGBATA — Coordination générale   (+229 01 96 48 46 48) </li>
            <li>BLECK STEEVEN KOCOUVISSO — Référent technique & conseil  (+229 01 61 03 37 90)</li>
            <li>MAWOUEGNIGAN GREGOIRE FANGNON — Développement & maintenance plateforme (+229 01 52 14 58 06 )</li>
        </ol>
    </p>

    <h2>3. Hébergement</h2>
    <p>
        La plateforme est hébergée sur un serveur VPS utilisant les technologies Linux (Ubuntu), Apache, PHP et MariaDB.
    </p>

    <h2>4. Utilisation des contenus</h2>
    <p>
        Les documents partagés sur la plateforme sont destinés exclusivement aux membres de la promotion.
        Toute utilisation extérieure doit être faite avec l’accord des auteurs concernés.
    </p>

    <h2>5. Données personnelles</h2>
    <p>
        Les données collectées (identifiants, emails, mots de passe) sont utilisées uniquement pour le fonctionnement
        de la plateforme (authentification et accès aux ressources).
        Aucune donnée n’est vendue, cédée ou exploitée à des fins commerciales.
    </p>

    <h2>6. Accès au site</h2>
    <p>
        L’accès à la plateforme est réservé aux utilisateurs autorisés disposant d’un compte valide.
    </p>

    <h2>7. Responsabilité</h2>
    <p>
        Les administrateurs s’efforcent d’assurer le bon fonctionnement de la plateforme.
        Toutefois, ils ne peuvent garantir une disponibilité permanente ni l’absence totale d’erreurs.
    </p>

    <h2>8. Évolution de la plateforme</h2>
    <p>
        La plateforme peut évoluer à tout moment afin d’améliorer les fonctionnalités,
        la sécurité et l’expérience utilisateur.
    </p>

    <hr>

</main>

<?php include __DIR__ . '/templates/footer.php'; ?>

</body>
</html>
