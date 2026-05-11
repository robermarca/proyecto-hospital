<?php
session_start();

$errores = $_SESSION['errores_registro'] ?? [];
$datos = $_SESSION['datos_registro'] ?? [];

unset($_SESSION['errores_registro']);
unset($_SESSION['datos_registro']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de usuario - CelCare</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>

    <main class="contenedor-formulario">

        <h1>Registro de usuario</h1>

        <?php if (!empty($errores)): ?>
            <div class="mensaje-error">
                <ul>
                    <?php foreach ($errores as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="guardar_usuario.php" method="POST">

            <label for="nombre">Nombre</label>
            <input type="text" name="nombre" id="nombre" value="<?= htmlspecialchars($datos['nombre'] ?? '') ?>"
            >

            <label for="apellidos">Apellidos</label>
            <input type="text" name="apellidos" id="apellidos"value="<?= htmlspecialchars($datos['apellidos'] ?? '') ?>"
            >

            <label for="email">Email</label>
            <input type="email" name="email" id="email" value="<?= htmlspecialchars($datos['email'] ?? '') ?>"
            >

            <label for="password">Contraseña</label>
            <input type="password" name="password" id="password">

            <label for="confirmar_password">Confirmar contraseña</label>
            <input type="password" name="confirmar_password" id="confirmar_password">

            <label for="rol">Rol</label>
            <select name="rol" id="rol">
                <option value="">Selecciona un rol</option>
                <option value="facultativo" <?= (($datos['rol'] ?? '') === 'facultativo') ? 'selected' : '' ?>>
                    Facultativo
                </option>
                <option value="celador" <?= (($datos['rol'] ?? '') === 'celador') ? 'selected' : '' ?>>
                    Celador
                </option>
            </select>

            <button type="submit">Crear cuenta</button>

        </form>

        <p>
            ¿Ya tienes cuenta?
            <a href="login.php">Inicia sesión</a>
        </p>

    </main>

</body>
</html>