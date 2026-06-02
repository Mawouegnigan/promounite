<?php
session_start();

/*
 * PROTECTION ADMIN GLOBALE
 */

function require_admin() {

    if (!isset($_SESSION['admin_id'])) {
        header("Location: ../login.php");
        exit;
    }

    // protection session expirée (30 min)
    if (isset($_SESSION['last_activity']) &&
        time() - $_SESSION['last_activity'] > 1800) {

        session_unset();
        session_destroy();

        header("Location: ../login.php");
        exit;
    }

    $_SESSION['last_activity'] = time();
}
