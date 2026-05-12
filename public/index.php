<?php
require_once __DIR__ . '/auth/auth.php';

echo '<h1>Bienvenido a CelCare</h1>';

echo '<pre>';
print_r($_SESSION['usuario'] ?? 'No hay usuario en sesión');
echo '</pre>';
?>