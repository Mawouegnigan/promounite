<?php

// ==========================
// CONFIGURATION GLOBALE (DEBUG)
// ==========================

// ACTIVER AFFICHAGE DES ERREURS (IMPORTANT POUR DEBUG)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// ACTIVER LOGS D'ERREURS
ini_set('log_errors', 1);

// Fichier de log global du projet
ini_set('error_log', __DIR__ . '/../php-error.log');
