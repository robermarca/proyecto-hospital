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
    t.fecha_solicitud
FROM traslados t
JOIN pacientes p ON t.id_paciente = p.id_paciente
JOIN ubicaciones o ON t.id_origen = o.id_ubicacion
JOIN ubicaciones d ON t.id_destino = d.id_ubicacion
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

<h1>Traslados</h1>

<?php if (count($traslados) > 0): ?>

    <?php foreach ($traslados as $t): ?>

        <div class="card <?php echo str_replace(' ', '_', $t['estado']); ?>">

            <strong>
                <?php echo $t['nombre'] . " " . $t['apellidos']; ?>
            </strong><br><br>
            <?php if (!empty($t['prueba_solicitada'])): ?>
                <span class="prueba">
    Prueba: <?php echo htmlspecialchars($t['prueba_solicitada']); ?><br><br>
<?php endif; ?>

             <?php echo $t['origen']; ?> → <?php echo $t['destino']; ?><br><br>

            Estado: <strong><?php echo $t['estado']; ?></strong><br><br>

            <span class="hora">
                <?php echo date('H:i', strtotime($t['fecha_solicitud'])); ?>
                </span><br>

                <span class="fecha">
    <?php echo date('d/m/y', strtotime($t['fecha_solicitud'])); ?>
                </span>

        </div>

    <?php endforeach; ?>

<?php else: ?>

    <p>No hay traslados registrados.</p>

<?php endif; ?>

</body>
</html>