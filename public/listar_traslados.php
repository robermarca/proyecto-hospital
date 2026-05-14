<?php

require_once __DIR__ . '/auth/auth.php';
require_once __DIR__ . '/../app/config/conexion.php';

$usuario = $_SESSION['usuario'];
$rol = $usuario['rol'];

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

<body class="dashboard-page">
<main class="dashboard-wrapper">

   <div class="dashboard-glow"></div>

    <section class="listado-header-sticky">

        <div class="listado-header-marca">
            <img src="imagenes/logo.png" alt="CelCare" class="listado-header-logo">
            <h1>Lista de traslados</h1>
        </div>

        <div class="listado-header-acciones">
            <?php if ($rol === 'facultativo'): ?>
                <a href="crear_traslado.php" class="dashboard-btn principal">
                    + Nuevo traslado
                </a>
            <?php endif; ?>
        </div>

    </section>

    <section class="listado-traslados">

        <?php if (count($traslados) > 0): ?>

            <?php foreach ($traslados as $t): ?>

                <article class="traslado-card <?= htmlspecialchars($t['estado']) ?>">

                    <div class="traslado-header">
                        <div class="paciente-info">
                            <h2>
                                <?= htmlspecialchars($t['nombre'] . " " . $t['apellidos']) ?>
                            </h2>

                            <p class="ruta-traslado">
                                <?= htmlspecialchars($t['origen']) ?>
                                <span class="flecha">→</span>
                                <?= htmlspecialchars($t['destino']) ?>
                            </p>
                        </div>

                        <div class="estado-badge <?= htmlspecialchars($t['estado']) ?>">
                            <?= strtoupper(htmlspecialchars(str_replace('_', ' ', $t['estado']))) ?>
                        </div>
                    </div>

                    <div class="traslado-body">

                        <div class="traslado-detalles">
                            <?php if (!empty($t['prueba_solicitada'])): ?>
                                <p>
                                    <strong>Prueba:</strong>
                                    <?= htmlspecialchars($t['prueba_solicitada']) ?>
                                </p>
                            <?php endif; ?>

                            <p>
                                <strong>Facultativo:</strong>
                                <?= htmlspecialchars($t['facultativo_nombre']) ?>
                            </p>

                            <p>
                                <strong>Celador:</strong>
                                <?= htmlspecialchars($t['celador_nombre']) ?>
                            </p>
                        </div>

                        <div class="traslado-fecha">
                            <span class="hora">
                                <?= date('H:i', strtotime($t['fecha_solicitud'])) ?>
                            </span>

                            <span class="fecha">
                                <?= date('d/m/y', strtotime($t['fecha_solicitud'])) ?>
                            </span>
                        </div>

                    </div>

                    <?php if (
                        $rol === 'celador' &&
                        $t['estado'] !== 'cancelado' &&
                        $t['estado'] !== 'completado'
                    ): ?>
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
                            <a href="ruta.php?id_traslado=<?= htmlspecialchars($t['id_traslado']) ?>" class="boton-accion ruta">Ver ruta </a>

                        </form>
                    <?php endif; ?>

                </article>

            <?php endforeach; ?>

        <?php else: ?>

            <section class="dashboard-card traslado-form-card">
                <h1>No hay traslados</h1>
                <p class="dashboard-saludo">
                    Todavía no hay traslados registrados.
                </p>
            </section>

        <?php endif; ?>

    </section>
    <a href="index.php" class="btn-volver-flotante">Volver</a>

</main>
</body>
</html>