<?php
// index.php

session_start();
if(isset($_SESSION['usuario_id'])) {
    header("Location: perfil.php");
    exit();
}

require_once 'conexion.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $correo = filter_input(INPUT_POST, 'correo', FILTER_SANITIZE_EMAIL);
    $contrasena = $_POST['contrasena'];

    $stmt = $pdo->prepare("SELECT id, contrasena FROM estudiantes WHERE correo = ?");
    $stmt->execute([$correo]);
    $usuario = $stmt->fetch();

    if ($usuario && ((strcmp($contrasena, $usuario['contrasena']) == 0)) /*password_verify($contrasena, $usuario['contrasena'])*/) {
        $_SESSION['usuario_id'] = $usuario['id'];
        header("Location: perfil.php");
        exit();
    }
    else if (strlen($correo) && strlen($contrasena))
    {
        $error = "Credenciales incorrectas";
    }
    else
    {
        $error = "Ambos campos requeridos";
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
</body>
</html>