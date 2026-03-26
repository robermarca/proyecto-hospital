<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "../app/config/conexion.php";

$sql = "
SELECT 
    t.id_traslado,
    p.nombre,
    p.apellidos,
    o.nombre AS origen,
    d.nombre AS destino,
    t.prueba_solicitada,
    t.estado,
    t.fecha_solicitud,
    CONCAT(f.nombre, ' ', f.apellidos) AS facultativo_nombre,
    CASE 
        WHEN c.id_usuario IS NULL THEN 'Sin asignar'
        ELSE CONCAT(c.nombre, ' ', c.apellidos)
    END AS celador_nombre
FROM traslados t
JOIN pacientes p ON t.id_paciente = p.id_paciente
JOIN ubicaciones o ON t.id_origen = o.id_ubicacion
JOIN ubicaciones d ON t.id_destino = d.id_ubicacion
JOIN usuarios f ON t.id_facultativo = f.id_usuario
LEFT JOIN usuarios c ON t.id_celador = c.id_usuario
ORDER BY t.fecha_solicitud DESC
";

$stmt = $conexion->query($sql);
$traslados = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>CelCare - Traslados</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>

    <div class="header-titulo">
        <h1>Lista de traslados</h1>
        <img src="imagenes/logo.png" alt="CelCare" class="logo">
    </div>

<?php if (count($traslados) > 0): ?>

    <?php foreach ($traslados as $t): ?>

        <div class="card <?php echo htmlspecialchars($t['estado']); ?>">

            <strong>
                <?php echo htmlspecialchars($t['nombre'] . " " . $t['apellidos']); ?>
            </strong><br><br>
            
            <?php echo htmlspecialchars($t['origen']); ?> → <?php echo htmlspecialchars($t['destino']); ?><br><br>

            <?php if (!empty($t['prueba_solicitada'])): ?>
                <span class="prueba">
                    Prueba: <?php echo htmlspecialchars($t['prueba_solicitada']); ?>
                </span><br><br>
            <?php endif; ?>

            <span><strong>Facultativo:</strong> <?php echo htmlspecialchars($t['facultativo_nombre']); ?></span><br><br>
            <span><strong>Celador:</strong> <?php echo htmlspecialchars($t['celador_nombre']); ?></span><br><br>

            <span class="estado-texto <?= htmlspecialchars($t['estado']); ?>">
    <strong>Estado:</strong> <?= htmlspecialchars($t['estado']); ?>
            </span><br><br>

            <span class="hora">
                <?php echo date('H:i', strtotime($t['fecha_solicitud'])); ?>
            </span><br>

            <span class="fecha">
                <?php echo date('d/m/y', strtotime($t['fecha_solicitud'])); ?>
            </span>

            <br><br>

            <?php if ($t['estado'] !== 'cancelado' && $t['estado'] !== 'completado'): ?>
                <form class="acciones-traslado" method="POST">

                    <input type="hidden" name="id_traslado" value="<?= htmlspecialchars($t['id_traslado']) ?>">

                    <select name="estado" class="select-estado">
                        <option value="pendiente" <?= $t['estado'] === 'pendiente' ? 'selected' : '' ?>>Pendiente</option>
                        <option value="en_curso" <?= $t['estado'] === 'en_curso' ? 'selected' : '' ?>>En curso</option>
                        <option value="completado" <?= $t['estado'] === 'completado' ? 'selected' : '' ?>>Completado</option>
                        <option value="pospuesto" <?= $t['estado'] === 'pospuesto' ? 'selected' : '' ?>>Pospuesto</option>
                    </select>

                    <button 
                        type="submit" 
                        formaction="actualizar_estado.php"
                        class="boton-accion actualizar">
                        Actualizar
                    </button>

                    <button 
                        type="submit" 
                        formaction="cancelar_traslado.php"
                        class="boton-accion cancelar">
                        Cancelar
                    </button>

                </form>
            <?php endif; ?>

        </div>

    <?php endforeach; ?>

<?php else: ?>

    <p>No hay traslados registrados.</p>

<?php endif; ?>

<br>
<div class="barra-inferior">
    <a href="crear_traslado.php" class="boton-enlace">+ Nuevo traslado</a>
</div>

</body>
</html>