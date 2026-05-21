<?php
session_start();

require_once __DIR__ . '/config/app.php';

// supprimer les données de session
$_SESSION = [];

// supprimer le cookie de session si existant
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// détruire la session
session_destroy();

// redirection
header("Location: login.php");
exit();
