<?php
require_once __DIR__ . '/auth.php';

if ($_SESSION['usuario']['rol'] !== 'facultativo') {
    header("Location: /proyecto-hospital/public/index.php");
    exit;
}