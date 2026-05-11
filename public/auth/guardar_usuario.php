<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();

require_once __DIR__ . '/../../app/config/conexion.php';

$errores = [];

$nombre = trim($_POST['nombre'] ?? '');
$apellidos = trim($_POST['apellidos'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$confirmarPassword = $_POST['confirmar_password'] ?? '';
$rol = $_POST['rol'] ?? '';

$rolesPermitidos = ['facultativo', 'celador'];

if ($nombre === '') {
    $errores[] = 'El nombre es obligatorio.';
}

if ($apellidos === '') {
    $errores[] = 'Los apellidos son obligatorios.';
}

if ($email === '') {
    $errores[] = 'El email es obligatorio.';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errores[] = 'El email no tiene un formato válido.';
}

if ($password === '') {
    $errores[] = 'La contraseña es obligatoria.';
} elseif (
    strlen($password) < 8 ||
    !preg_match('/[A-Z]/', $password) ||
    !preg_match('/[a-z]/', $password) ||
    !preg_match('/[0-9]/', $password)
) {
    $errores[] = 'La contraseña debe tener al menos 8 caracteres, una mayúscula, una minúscula y un número.';
}

if ($confirmarPassword === '') {
    $errores[] = 'Debes confirmar la contraseña.';
}

if ($password !== $confirmarPassword) {
    $errores[] = 'Las contraseñas no coinciden.';
}

if ($rol === '') {
    $errores[] = 'Debes seleccionar un rol.';
} elseif (!in_array($rol, $rolesPermitidos)) {
    $errores[] = 'El rol seleccionado no es válido.';
}

if (!empty($errores)) {
    $_SESSION['errores_registro'] = $errores;
    $_SESSION['datos_registro'] = [
        'nombre' => $nombre,
        'apellidos' => $apellidos,
        'email' => $email,
        'rol' => $rol
    ];

    header('Location: registro.php');
    exit;
}

try {
    $sql = "SELECT id_usuario FROM usuarios WHERE email = :email";
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if ($stmt->fetch()) {
        $_SESSION['errores_registro'] = ['Ya existe un usuario registrado con ese email.'];
        $_SESSION['datos_registro'] = [
            'nombre' => $nombre,
            'apellidos' => $apellidos,
            'email' => $email,
            'rol' => $rol
        ];

        header('Location: registro.php');
        exit;
    }

    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO usuarios 
            (nombre, apellidos, email, password_hash, rol)
            VALUES 
            (:nombre, :apellidos, :email, :password_hash, :rol)";

    $stmt = $conexion->prepare($sql);

    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':apellidos', $apellidos);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password_hash', $passwordHash);
    $stmt->bindParam(':rol', $rol);

    $stmt->execute();

    $_SESSION['mensaje_login'] = 'Usuario registrado correctamente. Ya puedes iniciar sesión.';

    header('Location: login.php');
    exit;

} catch (PDOException $e) {
    die('Error al registrar usuario: ' . $e->getMessage());
}