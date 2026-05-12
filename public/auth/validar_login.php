<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();

require_once __DIR__ . '/../../app/config/conexion.php';

$errores = [];

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if ($email === '') {
    $errores[] = 'El email es obligatorio.';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errores[] = 'El email no tiene un formato válido.';
}

if ($password === '') {
    $errores[] = 'La contraseña es obligatoria.';
}

if (!empty($errores)) {
    $_SESSION['errores_login'] = $errores;
    header('Location: login.php');
    exit;
}

try {
    $sql = "SELECT id_usuario, nombre, apellidos, email, password_hash, rol
            FROM usuarios
            WHERE email = :email
            LIMIT 1";

    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$usuario || !password_verify($password, $usuario['password_hash'])) {
        $_SESSION['errores_login'] = ['Email o contraseña incorrectos.'];
        header('Location: login.php');
        exit;
    }

    $_SESSION['usuario'] = [
        'id_usuario' => $usuario['id_usuario'],
        'nombre' => $usuario['nombre'],
        'apellidos' => $usuario['apellidos'],
        'email' => $usuario['email'],
        'rol' => $usuario['rol']
    ];

    $_SESSION['id_usuario'] = $usuario['id_usuario'];

    if ($usuario['rol'] === 'facultativo') {

    header('Location: ../crear_traslado.php');
    exit;
}

if ($usuario['rol'] === 'celador') {

    header('Location: ../listar_traslados.php');
    exit;


} 

header('Location: ../index.php');
exit;

}catch (PDOException $e) {
    die('Error al iniciar sesión: ' . $e->getMessage());
}