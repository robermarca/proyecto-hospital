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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CelCare | Registro</title>
    <link rel="stylesheet" href="../styles.css">
</head>

<body class="login-page">

<main class="login-wrapper">

    <section class="login-brand">
        <img class="registro-logo" src="../imagenes/logo.png" alt="Logo CelCare" class="login-logo">

        <p class="login-frase">
            Crea tu cuenta para gestionar traslados<br>
            de forma segura y eficiente.
        </p>
    </section>

    <section class="login-card">

        <h1>Registro</h1>

        <?php if (!empty($errores)): ?>
            <div class="errores-login">
                <?php foreach ($errores as $error): ?>
                    <p><?= htmlspecialchars($error) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form action="guardar_usuario.php" method="POST">

            <div class="input-group">
                <input 
                    type="text" 
                    name="nombre" 
                    placeholder="Nombre"
                    value="<?= htmlspecialchars($datos['nombre'] ?? '') ?>"
                    required>
            </div>

            <div class="input-group">
                <input 
                    type="text" 
                    name="apellidos" 
                    placeholder="Apellidos"
                    value="<?= htmlspecialchars($datos['apellidos'] ?? '') ?>"
                    required>
            </div>

            <div class="input-group">
                <input 
                    type="email" 
                    name="email" 
                    placeholder="Email"
                    value="<?= htmlspecialchars($datos['email'] ?? '') ?>"
                    required>
            </div>

            <div class="input-group">
                <input 
                    type="password" 
                    name="password" 
                    placeholder="Contraseña"
                    required>
            </div>

            <div class="input-group">
                <input 
                    type="password" 
                    name="confirmar_password" 
                    placeholder="Confirmar contraseña"
                    required>
            </div>

            <div class="input-group">
                <select name="rol" id="rol" required>
                    <option value="">Selecciona un rol</option>
                    <option value="facultativo" <?= (($datos['rol'] ?? '') === 'facultativo') ? 'selected' : '' ?>>
                        Facultativo
                    </option>
                    <option value="celador" <?= (($datos['rol'] ?? '') === 'celador') ? 'selected' : '' ?>>
                        Celador
                    </option>
                </select>
            </div>

            <button type="submit" class="btn-login">
                CREAR CUENTA
            </button>

        </form>

        <div class="login-separator">
            <span></span>
            <p>o</p>
            <span></span>
        </div>

        <p class="registro-texto">¿Ya tienes cuenta?</p>

        <a href="login.php" class="btn-registro">
            INICIA SESIÓN
        </a>

    </section>

</main>

</body>
</html>