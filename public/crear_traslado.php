<?php

require_once __DIR__ . '/auth/solo_facultativo.php';
require_once __DIR__ . '/../app/config/conexion.php';

$usuario = $_SESSION['usuario'];

$id_facultativo_seleccionado = $usuario['id_usuario'];

$texto_facultativo =
    $usuario['apellidos'] . ', ' . $usuario['nombre'];


$sqlPacientes = "SELECT id_paciente, nombre, apellidos FROM pacientes ORDER BY apellidos, nombre";
$stmtPacientes = $conexion->query($sqlPacientes);
$pacientes = $stmtPacientes->fetchAll(PDO::FETCH_ASSOC);

$sqlUbicaciones = "SELECT id_ubicacion, nombre, planta FROM ubicaciones ORDER BY nombre, planta";
$stmtUbicaciones = $conexion->query($sqlUbicaciones);
$ubicaciones = $stmtUbicaciones->fetchAll(PDO::FETCH_ASSOC);

$id_paciente_seleccionado = $_POST['id_paciente'] ?? '';
$id_origen_seleccionado = $_POST['id_origen'] ?? '';
$id_destino_seleccionado = $_POST['id_destino'] ?? '';


$texto_paciente = '';
$texto_origen = '';
$texto_destino = '';

foreach ($pacientes as $p) {
    if ((string)$p['id_paciente'] === (string)$id_paciente_seleccionado) {
        $texto_paciente = $p['apellidos'] . ', ' . $p['nombre'];
        break;
    }
}

foreach ($ubicaciones as $u) {
    if ((string)$u['id_ubicacion'] === (string)$id_origen_seleccionado) {
        $texto_origen = $u['nombre'] . ' - Planta ' . $u['planta'];
    }

    if ((string)$u['id_ubicacion'] === (string)$id_destino_seleccionado) {
        $texto_destino = $u['nombre'] . ' - Planta ' . $u['planta'];
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
<body class="dashboard-page">
<main class="dashboard-wrapper">

    <section class="dashboard-brand">
        <img src="imagenes/logo.png" alt="CelCare" class="dashboard-logo logo-traslado">
    </section>

    <div class="dashboard-glow"></div>

    <section class="dashboard-card traslado-form-card">
        <h1>Nuevo traslado</h1>
      

        <form method="POST" action="confirmar_traslado.php">
            <div class="campo">
                <label for="buscar_paciente">Paciente</label>
                <input type="text" id="buscar_paciente" name="buscar_paciente" placeholder="Escribe nombre o apellidos" autocomplete="off" required value="<?= htmlspecialchars($texto_paciente) ?>">

                <input type="hidden" name="id_paciente" id="id_paciente" value="<?= htmlspecialchars($id_paciente_seleccionado) ?>">
                <div id="lista_pacientes" class="lista-sugerencias"></div>
            </div>

            <br>

            <div class="campo">
                <label for="buscar_origen">Origen</label>
                <input type="text" name="buscar_origen" id="buscar_origen" placeholder="Buscar origen..." autocomplete="off" required value="<?= htmlspecialchars($texto_origen) ?>">

                <input type="hidden" name="id_origen" id="id_origen" value="<?= htmlspecialchars($id_origen_seleccionado) ?>">
                <div id="lista_origen" class="lista-sugerencias"></div>
            </div>

            <br>

            <div class="campo">
                <label for="buscar_destino">Destino</label>
                <input type="text" name="buscar_destino" id="buscar_destino" placeholder="Buscar destino..." autocomplete="off" required value="<?= htmlspecialchars($texto_destino) ?>">

                <input type="hidden" name="id_destino" id="id_destino" value="<?= htmlspecialchars($id_destino_seleccionado) ?>">
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
                    placeholder="Ej: TAC, escáner, ecocardiograma..."
                    value="<?= htmlspecialchars($_POST['prueba_solicitada'] ?? '') ?>"
                >
            </div>

            <br>

            <div class="campo">
                <label for="buscar_facultativo">Facultativo solicitante</label>

                <input type="text" id="buscar_facultativo" value="<?= htmlspecialchars($texto_facultativo) ?>" disabled>

                <input type="hidden" name="id_facultativo" id="id_facultativo" value="<?= htmlspecialchars($id_facultativo_seleccionado) ?>">
            </div>


            <br>

            <button type="submit">Revisar traslado</button>
        </form>

    <br>
    <a href="listar_traslados.php">Ver listado</a>
    </section>
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
</main>
</body>
</html>