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
    <link rel="stylesheet" href="../styles.css">
</head>
<body class="login-body">

    <main class="login-page">

        <section class="brand-panel">
            <img src="../assets/img/logo-celcare.png" class="logo-celcare" alt="Logo CelCare">

            <h1>CelCare</h1>
            <p>Gestión inteligente de traslados hospitalarios</p>
        </section>

        <section class="login-card">
            <h2>Iniciar sesión</h2>

            <form action="validar_login.php" method="POST">
                <label>Email</label>
                <input type="email" name="email">

                <label>Contraseña</label>
                <input type="password" name="password">

                <button type="submit">Entrar</button>
            </form>

            <p>¿No tienes cuenta? <a href="registro.php">Regístrate</a></p>
        </section>

        <section class="login-features">
            <div>Cercanía</div>
            <div>Cuidado</div>
            <div>En movimiento</div>
        </section>

    </main>

</body>
</html>