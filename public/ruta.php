<?php

require_once __DIR__ . '/auth/solo_celador.php';

$id_traslado = $_GET['id_traslado'] ?? null;

?>

<h1>Ruta traslado <?= htmlspecialchars($id_traslado) ?></h1>