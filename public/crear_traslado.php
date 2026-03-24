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

$ids_pacientes_validos = array_map('intval', array_column($pacientes, 'id_paciente'));
$ids_ubicaciones_validos = array_map('intval', array_column($ubicaciones, 'id_ubicacion'));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_paciente = $_POST['id_paciente'] ?? '';
    $id_origen = $_POST['id_origen'] ?? '';
    $id_destino = $_POST['id_destino'] ?? '';
    $id_usuario = null;
    $facultativo_solicitante = trim($_POST['facultativo_solicitante'] ?? '');
    $prueba_solicitada = trim($_POST['prueba_solicitada'] ?? '');
    $estado = 'pendiente';

    if ($id_paciente === '') {
        $errores[] = "Debes seleccionar un paciente.";
    } elseif (!ctype_digit($id_paciente)) {
        $errores[] = "El paciente seleccionado no es válido.";
    } elseif (!in_array((int)$id_paciente, $ids_pacientes_validos, true)) {
        $errores[] = "El paciente seleccionado no existe.";
    }

    if ($id_origen === '') {
        $errores[] = "Debes seleccionar un origen.";
    } elseif (!ctype_digit($id_origen)) {
        $errores[] = "El origen seleccionado no es válido.";
    } elseif (!in_array((int)$id_origen, $ids_ubicaciones_validos, true)) {
        $errores[] = "El origen seleccionado no existe.";
    }

    if ($id_destino === '') {
        $errores[] = "Debes seleccionar un destino.";
    } elseif (!ctype_digit($id_destino)) {
        $errores[] = "El destino seleccionado no es válido.";
    } elseif (!in_array((int)$id_destino, $ids_ubicaciones_validos, true)) {
        $errores[] = "El destino seleccionado no existe.";
    }

    if (
        $id_origen !== '' && ctype_digit($id_origen) &&
        $id_destino !== '' && ctype_digit($id_destino) &&
        $id_origen === $id_destino
    ) {
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

    if (empty($errores)) {
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
    <link rel="stylesheet" href="styles.css">
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
        <div class="campo">
            <label for="buscar_paciente">Paciente</label>
            <input type="text" id="buscar_paciente" name="buscar_paciente" placeholder="escribe nombre o apellidos" autocomplete="off" required>

            <input type="hidden" name="id_paciente" id="id_paciente">
             <div id="lista_pacientes" class="lista-sugerencias"></div>
        </div>

        <br>

        <div class="campo">
            <label for="buscar_origen">Origen</label>
            <input type="text" name="buscar_origen" id="buscar_origen" placeholder="Buscar origen..." autocomplete="off" required>

            <input type="hidden" name="id_origen" id="id_origen">
            <div id="lista_origen" class="lista-sugerencias"></div>
        </div>

        <br>

        <div class="campo">
            <label for="buscar_destino">Destino</label>
            <input type="text" name="buscar_destino" id="buscar_destino" placeholder="Buscar destino..." autocomplete="off" required>

            <input type="hidden" name="id_destino" id="id_destino">
            <div id="lista_destino" class="lista-sugerencias"></div>  
        </div>

        <br>

        <div class="campo">
            <label for="prueba_solicitada">Prueba solicitada:</label>
            <input
                type="text"
                name="prueba_solicitada"
                id="prueba_solicitada"
                maxlength="100"
                placeholder ="Ej: TAC, scanner, ecocardiograma..."
                value="<?= htmlspecialchars($_POST['prueba_solicitada'] ?? '') ?>"
            >
        </div>
        <br>

        <div class="campo">
            <label for="facultativo_solicitante">Facultativo solicitante:</label>
            <input
                type="text"
                name="facultativo_solicitante"
                id="facultativo_solicitante"
                value="<?= htmlspecialchars($_POST['facultativo_solicitante'] ?? '') ?>"
                required
            >
        </div>

        <br>

        <button type="submit">Guardar traslado</button>
    </form>

    <br>
    <a href="listar_traslados.php">Ver listado</a>

    <script>
    const pacientes = <?= json_encode(
        array_map(function($p) {
            return [
                'id' => $p['id_paciente'],
                'nombre' => $p['apellidos'] . ', ' . $p['nombre']
            ];
        }, $pacientes)
    ); ?>;
    const ubicaciones = <?= json_encode(
        array_map(function($u){
            return [
                'id' => $u['id_ubicacion'],
                'nombre' => $u['nombre'] . ' - Planta ' . $u['planta']
            ];
        }, $ubicaciones)
    ); ?>;
    </script>
    <script src="script.js"></script>
</body>
</html>