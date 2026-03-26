<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../app/config/conexion.php';

$id_traslado = $_POST['id_traslado'] ?? '';
$estado = $_POST['estado'] ?? '';

$estadosValidos = ['pendiente', 'en_curso', 'completado', 'pospuesto'];

if ($id_traslado === '' || !ctype_digit($id_traslado)) {
    die("ID de traslado no válido.");
}

if (!in_array($estado, $estadosValidos, true)) {
    die("Estado no válido.");
}

$sqlComprobar = "SELECT id_traslado, estado FROM traslados WHERE id_traslado = :id_traslado";
$stmtComprobar = $conexion->prepare($sqlComprobar);
$stmtComprobar->execute([
    ':id_traslado' => (int)$id_traslado
]);

$traslado = $stmtComprobar->fetch(PDO::FETCH_ASSOC);

if (!$traslado) {
    die("El traslado no existe.");
}

$sql = "UPDATE traslados
        SET estado = :estado
        WHERE id_traslado = :id_traslado";

$stmt = $conexion->prepare($sql);
$stmt->execute([
    ':estado' => $estado,
    ':id_traslado' => (int)$id_traslado
]);

header('Location: listar_traslados.php');
exit;
?>