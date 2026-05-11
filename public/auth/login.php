<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();

$mensaje = $_SESSION['mensaje_login'] ?? '';
$errores = $_SESSION['errores_login'] ?? [];

unset($_SESSION['mensaje_login']);
unset($_SESSION['errores_login']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login - CelCare</title>
</head>
<body>

    <h1>Iniciar sesión</h1>

    <?php if ($mensaje): ?>
        <div class="mensaje-exito">
            <?= htmlspecialchars($mensaje) ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($errores)): ?>
        <div class="mensaje-error">
            <ul>
                <?php foreach ($errores as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="validar_login.php" method="POST">

        <label for="email">Email</label>
        <input type="email" name="email" id="email">

        <label for="password">Contraseña</label>
        <input type="password" name="password" id="password" minlength="8"
    pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}"
    title="Mínimo 8 caracteres, una mayúscula, una minúscula y un número">

        <button type="submit">Iniciar sesión</button>

    </form>

    <p>
        ¿No tienes cuenta?
        <a href="registro.php">Regístrate</a>
    </p>

</body>
</html>