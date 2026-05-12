<?php
require_once __DIR__ . '/../auth/solo_celador.php';

$usuario = $_SESSION['usuario'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel Celador - CelCare</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body class="dashboard-page">

<main class="dashboard-wrapper">

    <section class="dashboard-brand">
        <img src="../imagenes/tituLogo.png" alt="CelCare" class="dashboard-logo">
    </section>

    <div class="dashboard-glow"></div>

    <section class="dashboard-card">

        <h1>Panel celador</h1>

        <p class="dashboard-saludo">
            Bienvenido/a <?= htmlspecialchars($usuario['nombre']) ?>
            <?= htmlspecialchars($usuario['apellidos']) ?>
        </p>

        <div class="dashboard-actions">
            <a href="../listar_traslados.php" class="dashboard-btn principal">
                Gestionar traslados
            </a>

            <a href="../auth/logout.php" class="dashboard-btn salir">
                Cerrar sesión
            </a>
        </div>

    </section>

</main>

</body>
</html>