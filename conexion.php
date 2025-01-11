<?php
// conexion.php

$host = 'localhost';
$db   = 'gestion_academica';
$user = 'tu_usuario';
$pass = 'tu_contraseña';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}