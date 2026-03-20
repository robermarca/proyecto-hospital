<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../app/config/conexion.php';

$errores = [];


$sqlPacientes = "SELECT id_paciente, nombre, apellidos FROM pacientes ORDER BY apellidos, nombre";
$stmtPacientes = $conexion->query($sqlPacientes);
$pacientes = $stmtPacientes->fetchAll(PDO::FETCH_ASSOC);

$sqlUbicaciones = "SELECT id_ubicacion, nombre, planta FROM ubicaciones ORDER BY nombre, planta";
$stmtUbicaciones = $conexion->query($sqlUbicaciones);
$ubicaciones = $stmtUbicaciones->fetchAll(PDO::FETCH_ASSOC);

$sqlUsuarios = "SELECT id_usuario, nombre, apellidos FROM usuarios ORDER BY apellidos, nombre";
$stmtUsuarios = $conexion->query($sqlUsuarios);
$usuarios = $stmtUsuarios->fetchAll(PDO::FETCH_ASSOC);


$ids_pacientes_validos = array_map('intval', array_column($pacientes, 'id_paciente'));
$ids_ubicaciones_validos = array_map('intval', array_column($ubicaciones, 'id_ubicacion'));
$ids_usuarios_validos = array_map('intval', array_column($usuarios, 'id_usuario'));


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_paciente = $_POST['id_paciente'] ?? '';
    $id_origen = $_POST['id_origen'] ?? '';
    $id_destino = $_POST['id_destino'] ?? '';
    $id_usuario = $_POST['id_usuario'] ?? '';
    $facultativo_solicitante = trim($_POST['facultativo_solicitante'] ?? '');
    $estado = trim($_POST['estado'] ?? '');

    if ($facultativo_solicitante === '') {
        $facultativo_solicitante = null;
    }

    $estados_validos = ['pendiente', 'en_curso', 'completado', 'cancelado', 'postpuesto'];

    if ($id_paciente === '') {
        $errores[] = "Debes seleccionar un paciente.";
    }

    if ($id_origen === '') {
        $errores[] = "Debes seleccionar un origen.";
    }

    if ($id_destino === '') {
        $errores[] = "Debes seleccionar un destino.";
    }

    if ($id_usuario === '') {
        $errores[] = "Debes seleccionar un usuario.";
    }

    if ($id_paciente !== '' && !ctype_digit($id_paciente)) {
        $errores[] = "El paciente seleccionado no es válido.";
    }

    if ($id_origen !== '' && !ctype_digit($id_origen)) {
        $errores[] = "El origen seleccionado no es válido.";
    }

    if ($id_destino !== '' && !ctype_digit($id_destino)) {
        $errores[] = "El destino seleccionado no es válido.";
    }

    if ($id_usuario !== '' && !ctype_digit($id_usuario)) {
        $errores[] = "El usuario seleccionado no es válido.";
    }

    if ($id_paciente !== '' && ctype_digit($id_paciente) && !in_array((int)$id_paciente, $ids_pacientes_validos, true)) {
        $errores[] = "El paciente seleccionado no existe.";
    }

    if ($id_origen !== '' && ctype_digit($id_origen) && !in_array((int)$id_origen, $ids_ubicaciones_validos, true)) {
        $errores[] = "El origen seleccionado no existe.";
    }

    if ($id_destino !== '' && ctype_digit($id_destino) && !in_array((int)$id_destino, $ids_ubicaciones_validos, true)) {
        $errores[] = "El destino seleccionado no existe.";
    }

    if ($id_usuario !== '' && ctype_digit($id_usuario) && !in_array((int)$id_usuario, $ids_usuarios_validos, true)) {
        $errores[] = "El usuario seleccionado no existe.";
    }

    if ($id_origen !== '' && $id_destino !== '' && $id_origen === $id_destino) {
        $errores[] = "El origen y el destino no pueden ser el mismo.";
    }

    if ($estado === '') {
        $errores[] = "Debes seleccionar un estado.";
    } elseif (!in_array($estado, $estados_validos, true)) {
        $errores[] = "El estado seleccionado no es válido.";
    }

    if (empty($errores)) {
        $sql = "INSERT INTO traslados (
                    id_paciente,
                    id_origen,
                    id_destino,
                    id_usuario,
                    facultativo_solicitante,
                    estado,
                    fecha_solicitud
                ) VALUES (
                    :id_paciente,
                    :id_origen,
                    :id_destino,
                    :id_usuario,
                    :facultativo_solicitante,
                    :estado,
                    NOW()
                )";

        $stmt = $conexion->prepare($sql);
        $stmt->execute([
            ':id_paciente' => (int)$id_paciente,
            ':id_origen' => (int)$id_origen,
            ':id_destino' => (int)$id_destino,
            ':id_usuario' => (int)$id_usuario,
            ':facultativo_solicitante' => $facultativo_solicitante,
            ':estado' => $estado
        ]);

        header('Location: listar_traslados.php');
        exit;
    }
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

    <?php if (!empty($errores)): ?>
        <ul style="color: red;">
            <?php foreach ($errores as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form method="POST" action="">
        <div>
            <label for="id_paciente">Paciente:</label>
            <select name="id_paciente" id="id_paciente" required>
                <option value="">-- Selecciona paciente --</option>
                <?php foreach ($pacientes as $paciente): ?>
                    <option
                        value="<?= htmlspecialchars($paciente['id_paciente']) ?>"
                        <?= (($_POST['id_paciente'] ?? '') == $paciente['id_paciente']) ? 'selected' : '' ?>
                    >
                        <?= htmlspecialchars($paciente['apellidos'] . ', ' . $paciente['nombre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <br>

        <div>
            <label for="id_origen">Origen:</label>
            <select name="id_origen" id="id_origen" required>
                <option value="">-- Selecciona origen --</option>
                <?php foreach ($ubicaciones as $ubicacion): ?>
                    <option
                        value="<?= htmlspecialchars($ubicacion['id_ubicacion']) ?>"
                        <?= (($_POST['id_origen'] ?? '') == $ubicacion['id_ubicacion']) ? 'selected' : '' ?>
                    >
                        <?= htmlspecialchars($ubicacion['nombre'] . ' - Planta ' . $ubicacion['planta']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <br>

        <div>
            <label for="id_destino">Destino:</label>
            <select name="id_destino" id="id_destino" required>
                <option value="">-- Selecciona destino --</option>
                <?php foreach ($ubicaciones as $ubicacion): ?>
                    <option
                        value="<?= htmlspecialchars($ubicacion['id_ubicacion']) ?>"
                        <?= (($_POST['id_destino'] ?? '') == $ubicacion['id_ubicacion']) ? 'selected' : '' ?>
                    >
                        <?= htmlspecialchars($ubicacion['nombre'] . ' - Planta ' . $ubicacion['planta']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <br>

        <div>
            <label for="id_usuario">Usuario / Celador:</label>
            <select name="id_usuario" id="id_usuario" required>
                <option value="">-- Selecciona usuario --</option>
                <?php foreach ($usuarios as $usuario): ?>
                    <option
                        value="<?= htmlspecialchars($usuario['id_usuario']) ?>"
                        <?= (($_POST['id_usuario'] ?? '') == $usuario['id_usuario']) ? 'selected' : '' ?>
                    >
                        <?= htmlspecialchars($usuario['apellidos'] . ', ' . $usuario['nombre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <br>

        <div>
            <label for="facultativo_solicitante">Facultativo solicitante:</label>
            <input
                type="text"
                name="facultativo_solicitante"
                id="facultativo_solicitante"
                value="<?= htmlspecialchars($_POST['facultativo_solicitante'] ?? '') ?>"
            >
        </div>

        <br>

        <div>
            <label for="estado">Estado:</label>
            <select name="estado" id="estado" required>
                <option value="">-- Selecciona estado --</option>
                <option value="pendiente" <?= (($_POST['estado'] ?? '') === 'pendiente') ? 'selected' : '' ?>>Pendiente</option>
                <option value="en_curso" <?= (($_POST['estado'] ?? '') === 'en_curso') ? 'selected' : '' ?>>En curso</option>
                <option value="completado" <?= (($_POST['estado'] ?? '') === 'completado') ? 'selected' : '' ?>>Completado</option>
                <option value="cancelado" <?= (($_POST['estado'] ?? '') === 'cancelado') ? 'selected' : '' ?>>Cancelado</option>
                <option value="postpuesto" <?= (($_POST['estado'] ?? '') === 'postpuesto') ? 'selected' : '' ?>>Postpuesto</option>
            </select>
        </div>

        <br>

        <button type="submit">Guardar traslado</button>
    </form>

    <br>
    <a href="listar_traslados.php">Volver al listado</a>
</body>
</html>