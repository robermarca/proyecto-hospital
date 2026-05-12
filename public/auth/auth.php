<?php
session_start();
if(!isset($_SESSION["id_usuario"])){
    header("Location: /proyecto-hospital/public/auth/login.php");
    exit;
}
?>