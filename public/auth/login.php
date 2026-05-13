<?php

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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CelCare | Login</title>
    <link rel="stylesheet" href="../styles.css">
</head>

<body class="login-page">

    <main class="login-wrapper">

        <section class="login-brand">
            <img src="../imagenes/tituLogo.png" alt="Logo CelCare" class="login-logo">

            <p class="login-frase">
                Conectamos personas, optimizamos traslados,<br>
                cuidamos lo que importa.
            </p>
        </section>

        <section class="login-card">

            <h1>Iniciar sesión</h1>

            <?php if (!empty($mensaje)): ?>
                <p class="mensaje-login"><?= htmlspecialchars($mensaje) ?></p>
            <?php endif; ?>

            <?php if (!empty($errores)): ?>
                <div class="errores-login">
                    <?php foreach ($errores as $error): ?>
                        <p><?= htmlspecialchars($error) ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form action="validar_login.php" method="POST">

                <div class="input-group">
                    <input 
                        type="email"
                        name="email"
                        placeholder="Usuario / email"
                        required>
                </div>

                <div class="input-group">
                    <input 
                        type="password"
                        name="password"
                        placeholder="Contraseña"
                        required>
                </div>

                <button type="submit" class="btn-login">
                    INICIAR SESIÓN
                </button>

            </form>

            <div class="login-separator">
                <span></span>
                <p>o</p>
                <span></span>
            </div>

            <p class="registro-texto">¿No tienes cuenta?</p>

            <a href="registro.php" class="btn-registro">
                REGÍSTRATE AQUÍ
            </a>

        </section>

    </main>

</body>
</html>