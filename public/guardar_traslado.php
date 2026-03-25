<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../app/config/conexion.php';

$id_paciente = $_POST['id_paciente'] ?? '';
$id_origen = $_POST['id_origen'] ?? '';
$id_destino = $_POST['id_destino'] ?? '';
$facultativo_solicitante = trim($_POST['facultativo_solicitante'] ?? '');
$prueba_solicitada = trim($_POST['prueba_solicitada'] ?? '');

$errores = [];

if ($id_paciente === '' || !ctype_digit($id_paciente)) {
    $errores[] = "Paciente no válido.";
}

if ($id_origen === '' || !ctype_digit($id_origen)) {
    $errores[] = "Origen no válido.";
}

if ($id_destino === '' || !ctype_digit($id_destino)) {
    $errores[] = "Destino no válido.";
}

if ($id_origen !== '' && $id_destino !== '' && $id_origen === $id_destino) {
    $errores[] = "El origen y el destino no pueden ser el mismo.";
}

if ($facultativo_solicitante === '') {
    $errores[] = "Debes indicar el facultativo solicitante.";
}

if ($prueba_solicitada !== '' && mb_strlen($prueba_solicitada) > 100) {
    $errores[] = "La prueba solicitada no puede superar los 100 caracteres.";
}

if ($prueba_solicitada === '') {
    $prueba_solicitada = null;
}

if (!empty($errores)) {
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Error al guardar traslado</title>
        <link rel="stylesheet" href="styles.css">
    </head>
    <body>
        <h1>Error al guardar traslado</h1>

        <div class="card pendiente">
            <ul style="color:red;">
                <?php foreach ($errores as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>

            <a href="crear_traslado.php" class="boton-enlace">Volver a nuevo traslado</a>
        </div>
    </body>
    </html>
    <?php
    exit;
}

$id_usuario = null;
$estado = 'pendiente';

$sql = "INSERT INTO traslados (
            id_paciente,
            id_origen,
            id_destino,
            id_usuario,
            facultativo_solicitante,
            prueba_solicitada,
            estado,
            fecha_solicitud
        ) VALUES (
            :id_paciente,
            :id_origen,
            :id_destino,
            :id_usuario,
            :facultativo_solicitante,
            :prueba_solicitada,
            :estado,
            NOW()
        )";

$stmt = $conexion->prepare($sql);
$stmt->execute([
    ':id_paciente' => (int)$id_paciente,
    ':id_origen' => (int)$id_origen,
    ':id_destino' => (int)$id_destino,
    ':id_usuario' => $id_usuario,
    ':facultativo_solicitante' => $facultativo_solicitante,
    ':prueba_solicitada' => $prueba_solicitada,
    ':estado' => $estado
]);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Traslado guardado</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

    <h1>Traslado guardado</h1>

    <div class="card pendiente">
        <strong>El traslado se ha guardado correctamente.</strong><br><br>

        <a href="crear_traslado.php" class="boton-enlace">Nuevo traslado</a>
        <a href="listar_traslados.php" class="boton-enlace">Ver listado</a>
    </div>

</body>
</html>