<?php
// perfil.php

session_start();

// Manejar el logout
if (isset($_GET['logout']) && $_GET['logout'] == '1') {
    session_unset();  // Elimina todas las variables de sesión
    session_destroy(); // Destruye la sesión actual
    header("Location: index.php"); // Redirige al usuario al inicio de sesión
    exit();
}

if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit();
}

require_once 'conexion.php';

$stmt = $pdo->prepare("SELECT * FROM estudiantes WHERE id = ?");
$stmt->execute([$_SESSION['usuario_id']]);
$estudiante = $stmt->fetch();

$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING);
    $apellido = filter_input(INPUT_POST, 'apellido', FILTER_SANITIZE_STRING);
    $carrera = filter_input(INPUT_POST, 'carrera', FILTER_SANITIZE_STRING);

    $stmt = $pdo->prepare("UPDATE estudiantes SET nombre = ?, apellido = ?, carrera = ? WHERE id = ?");
    if ($stmt->execute([$nombre, $apellido, $carrera, $_SESSION['usuario_id']])) {
        $mensaje = "Información actualizada correctamente";
        $estudiante['nombre'] = $nombre;
        $estudiante['apellido'] = $apellido;
        $estudiante['carrera'] = $carrera;
    } else {
        $mensaje = "Error al actualizar la información";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil - Sistema de Gestión Académica</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <div class="container">
        <h1>Perfil del Estudiante</h1>
        <?php if ($mensaje): ?>
            <p class="mensaje"><?php echo htmlspecialchars($mensaje); ?></p>
        <?php endif; ?>
        <form action="" method="post">
            <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($estudiante['nombre']); ?>" required>
            </div>
            <div class="form-group">
                <label for="apellido">Apellido:</label>
                <input type="text" id="apellido" name="apellido" value="<?php echo htmlspecialchars($estudiante['apellido']); ?>" required>
            </div>
            <div class="form-group">
                <label for="matricula">Matrícula:</label>
                <input type="text" id="matricula" value="<?php echo htmlspecialchars($estudiante['matricula']); ?>" readonly>
            </div>
            <div class="form-group">
                <label for="carrera">Carrera:</label>
                <input type="text" id="carrera" name="carrera" value="<?php echo htmlspecialchars($estudiante['carrera']); ?>" required>
            </div>
            <div class="form-group">
                <label for="correo">Correo Electrónico:</label>
                <input type="email" id="correo" value="<?php echo htmlspecialchars($estudiante['correo']); ?>" readonly>
            </div>
            <div class="form-group">
                <label for="promedio">Promedio Académico:</label>
                <input type="text" id="promedio" value="<?php echo number_format($estudiante['promedio'], 2); ?>" readonly>
            </div>
            <button type="submit">Actualizar Información</button>
        </form>
        <a href="perfil.php?logout=1" class="logout">Cerrar Sesión</a>
    </div>
    <?php include('footer.html'); ?>
</body>
</html>
