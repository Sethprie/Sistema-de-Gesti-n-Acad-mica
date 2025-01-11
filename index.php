<?php
// index.php

session_start();
if (isset($_SESSION['usuario_id'])) {
    if ($_SESSION['profe']) {
        header("Location: perfilprofe.php");
    } else {
        header("Location: perfil.php");
    }
    exit();
}

require_once 'conexion.php';

$error = ''; // Variable de error

function login($prepstate, $isprofe)
{
    global $pdo; // Asegurarse de que la variable $pdo esté accesible dentro de la función

    $correo = filter_input(INPUT_POST, 'correo', FILTER_SANITIZE_EMAIL);
    $contrasena = $_POST['contrasena'];

    $stmt = $pdo->prepare($prepstate);
    $stmt->execute([$correo]);
    $usuario = $stmt->fetch();

    if ($usuario && (strcmp($contrasena, $usuario['contrasena']) == 0)) {
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['profe'] = $isprofe;
        if ($isprofe)
            header("Location: perfilprofe.php");
        else
            header("Location: perfil.php");
        exit();
    }
    return null; // No hay error, pero el correo no coincide
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $error = login("SELECT id, contrasena FROM estudiantes WHERE correo = ?", false);

    // Si no encontró al usuario en la tabla de estudiantes, busca en la tabla de profesores
    if (!$error) {
        $error = login("SELECT id, contrasena FROM profesor WHERE correo = ?", true);
    }

    // Si no se encontró el correo ni en estudiantes ni en profesores
    if (!$error && (strlen($_POST['correo']) > 0 && strlen($_POST['contrasena']) > 0)) {
        $error = "Credenciales incorrectas"; // Mostrar un error en caso de no encontrar el usuario en ambas tablas
    } elseif (strlen($_POST['correo']) == 0 || strlen($_POST['contrasena']) == 0) {
        $error = "Ambos campos son requeridos"; // Mostrar un error si algún campo está vacío
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema de Gestión Académica</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <div class="container">
        <h1>Sistema de Gestión Académica</h1>
        <form action="" method="post">
            <h2>Iniciar Sesión</h2>
            <?php if ($error): ?>
                <p class="error"><?php echo $error; ?></p>
            <?php endif; ?>
            <div class="form-group">
                <label for="correo">Correo Electrónico:</label>
                <input type="email" id="correo" name="correo" required>
            </div>
            <div class="form-group">
                <label for="contrasena">Contraseña:</label>
                <input type="password" id="contrasena" name="contrasena" required>
            </div>
            <button type="submit">Iniciar Sesión</button>
        </form>
    </div>
    <?php include('footer.html'); ?>
</body>
</html>
