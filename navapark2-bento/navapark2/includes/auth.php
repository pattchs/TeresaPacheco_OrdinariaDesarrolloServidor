<?php
// includes/auth.php - Funciones de autenticación y sesión

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function estaLogueado() {
    return isset($_SESSION['usuario_id']);
}

function esAdmin() {
    return isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin';
}

function requiereLogin() {
    if (!estaLogueado()) {
        header('Location: ../index.php');
        exit;
    }
}

function requiereAdmin() {
    if (!estaLogueado() || !esAdmin()) {
        header('Location: ../index.php');
        exit;
    }
}

function calcularEdad($fecha_nacimiento) {
    $hoy = new DateTime();
    $nacimiento = new DateTime($fecha_nacimiento);
    return $hoy->diff($nacimiento)->y;
}
