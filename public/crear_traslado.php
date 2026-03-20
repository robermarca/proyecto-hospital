<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../app/config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_paciente = $_POST['id_paciente'] ?? '';
    $id_origen = $_POST['id_origen'] ?? '';
    $id_destino = $_POST['id_destino'] ?? '';
    $id_usuario = $_POST['id_usuario'] ?? '';
    $facultativo_solicitante = trim($_POST['facultativo_solicitante'] ?? '');

if ($facultativo_solicitante === '') {
    $facultativo_solicitante = null;
}
    $estado = trim($_POST['estado'] ?? '');
    $estados_validos = ['pendiente', 'en_curso', 'completado', 'cancelado', 'postpuesto'];

    if (!in_array($estado, $estados_validos, true)) {
        die('Estado no válido');
    }

    $sql = "INSERT INTO traslados (id_paciente, id_origen, id_destino, id_usuario, facultativo_solicitante, estado, fecha_solicitud)
            VALUES (:id_paciente, :id_origen, :id_destino, :id_usuario, :facultativo_solicitante, :estado, NOW())";

    $stmt = $conexion->prepare($sql);
    $stmt->execute([
        ':id_paciente' => $id_paciente,
        ':id_origen' => $id_origen,
        ':id_destino' => $id_destino,
        ':id_usuario' => $id_usuario,
        ':facultativo_solicitante' => $facultativo_solicitante,
        ':estado' => $estado
    ]);

    header('Location: listar_traslados.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear traslado</title>
</head>
<body>
    <h1>Nuevo traslado</h1>

    <form method="POST" action="">
        <div>
            <label for="id_paciente">ID paciente:</label>
            <input type="number" name="id_paciente" id="id_paciente" required>
        </div>

        <br>

        <div>
            <label for="id_origen">ID origen:</label>
            <input type="number" name="id_origen" id="id_origen" required>
        </div>

        <br>

        <div>
            <label for="id_destino">ID destino:</label>
            <input type="number" name="id_destino" id="id_destino" required>
        </div>

        <br>

        <div>
            <label for="id_usuario">ID usuario:</label>
            <input type="number" name="id_usuario" id="id_usuario" required>
        </div>

        <br>

        <div>
            <label for="facultativo_solicitante">Facultativo solicitante:</label>
            <input type="text" name="facultativo_solicitante" id="facultativo_solicitante">
        </div>

        <br>

        <div>
    <label for="estado">Estado:</label>
    <select name="estado" id="estado" required>
        <option value="">-- Selecciona --</option>
        <option value="pendiente">Pendiente</option>
        <option value="en_curso">En curso</option>
        <option value="completado">Completado</option>
        <option value="cancelado">Cancelado</option>
        <option value="postpuesto">Postpuesto</option>
    </select>
</div>

        <br>

        <button type="submit">Guardar traslado</button>
    </form>

    <br>
    <a href="listar_traslados.php">Volver al listado</a>
</body>
</html>