<?php

require_once __DIR__ . '/auth/solo_facultativo.php';
require_once __DIR__ . '/../app/config/conexion.php';

$id_paciente = $_POST['id_paciente'] ?? '';
$id_origen = $_POST['id_origen'] ?? '';
$id_destino = $_POST['id_destino'] ?? '';
$id_facultativo = $_POST['id_facultativo'] ?? '';
$prueba_solicitada = trim($_POST['prueba_solicitada'] ?? '');


if ($id_paciente === '' || $id_origen === '' || $id_destino === '' || $id_facultativo === '') {
    die("Faltan datos obligatorios para confirmar el traslado.");
}

$sqlPaciente = "SELECT nombre, apellidos FROM pacientes WHERE id_paciente = :id_paciente";
$stmtPaciente = $conexion->prepare($sqlPaciente);
$stmtPaciente->execute([':id_paciente' => (int)$id_paciente]);
$paciente = $stmtPaciente->fetch(PDO::FETCH_ASSOC);

$sqlOrigen = "SELECT nombre, planta FROM ubicaciones WHERE id_ubicacion = :id_origen";
$stmtOrigen = $conexion->prepare($sqlOrigen);
$stmtOrigen->execute([':id_origen' => (int)$id_origen]);
$origen = $stmtOrigen->fetch(PDO::FETCH_ASSOC);

$sqlDestino = "SELECT nombre, planta FROM ubicaciones WHERE id_ubicacion = :id_destino";
$stmtDestino = $conexion->prepare($sqlDestino);
$stmtDestino->execute([':id_destino' => (int)$id_destino]);
$destino = $stmtDestino->fetch(PDO::FETCH_ASSOC);

$sqlFacultativo = "SELECT nombre, apellidos FROM usuarios WHERE id_usuario = :id_facultativo AND rol = 'facultativo'";
$stmtFacultativo = $conexion->prepare($sqlFacultativo);
$stmtFacultativo->execute([':id_facultativo' => (int)$id_facultativo]);
$facultativo = $stmtFacultativo->fetch(PDO::FETCH_ASSOC);

if (!$paciente || !$origen || !$destino || !$facultativo) {
    die("Alguno de los datos seleccionados no existe.");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Confirmar traslado</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body class="dashboard-page">
<main class="dashboard-wrapper">

    <section class="dashboard-brand">
        <img src="imagenes/logo.png" alt="CelCare" class="dashboard-logo logo-traslado">
    </section>

    <div class="dashboard-glow"></div>

    <section class="dashboard-card traslado-form-card">
        <h1>Confirmar traslado</h1>

        <div class="resumen-traslado pendiente">
            <p><strong>Paciente:</strong> <?= htmlspecialchars($paciente['apellidos'] . ', ' . $paciente['nombre']) ?></p>
            <p><strong>Origen:</strong> <?= htmlspecialchars($origen['nombre']) ?> - Planta <?= htmlspecialchars($origen['planta']) ?></p>
            <p><strong>Destino:</strong> <?= htmlspecialchars($destino['nombre']) ?> - Planta <?= htmlspecialchars($destino['planta']) ?></p>
            <p><strong>Prueba solicitada:</strong> <?= htmlspecialchars($prueba_solicitada ?: 'No indicada') ?></p>
            <p><strong>Facultativo solicitante:</strong> <?= htmlspecialchars($facultativo['apellidos'] . ', ' . $facultativo['nombre']) ?></p>
        </div>

        <form action="guardar_traslado.php" method="POST">
            <input type="hidden" name="id_paciente" value="<?= htmlspecialchars($id_paciente) ?>">
            <input type="hidden" name="id_origen" value="<?= htmlspecialchars($id_origen) ?>">
            <input type="hidden" name="id_destino" value="<?= htmlspecialchars($id_destino) ?>">
            <input type="hidden" name="prueba_solicitada" value="<?= htmlspecialchars($prueba_solicitada) ?>">
            <input type="hidden" name="id_facultativo" value="<?= htmlspecialchars($id_facultativo) ?>">

            <button type="submit" class="btn-confirmar">Confirmar traslado</button>
        </form>

        <form action="crear_traslado.php" method="POST">
            <input type="hidden" name="id_paciente" value="<?= htmlspecialchars($id_paciente) ?>">
            <input type="hidden" name="id_origen" value="<?= htmlspecialchars($id_origen) ?>">
            <input type="hidden" name="id_destino" value="<?= htmlspecialchars($id_destino) ?>">
            <input type="hidden" name="prueba_solicitada" value="<?= htmlspecialchars($prueba_solicitada) ?>">
            <input type="hidden" name="id_facultativo" value="<?= htmlspecialchars($id_facultativo) ?>">
            <button type="submit" class="btn-secundario">Volver y corregir</button>
        </form>
    </section>
</main>    
</body>
</html>