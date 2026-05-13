<?php

require_once __DIR__ . '/auth/auth.php';

$rol = $_SESSION['usuario']['rol'];

if ($rol === 'facultativo') {
    header('Location: facultativo/panel_facultativo.php');
    exit;
}

if ($rol === 'celador') {
    header('Location: celador/panel_celador.php');
    exit;
}

session_destroy();

header('Location: auth/login.php');
exit;