<?php
session_start();
include "db.php";

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM usuarios WHERE email='$email' AND password='$password'";
    $resultado = $conn->query($sql);

    if ($resultado->num_rows > 0) {
        $usuario = $resultado->fetch_assoc();
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['nombre'] = $usuario['nombre'];
        header("Location: index.php");
        exit();
    } else {
        $mensaje = "⚠️ Email o contraseña incorrectos";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="stylesLogin.css">
</head>
<body class="tema-rojo">
    <div class="form-container">
        <h1>🔑 Iniciar Sesión</h1>
        <?php if ($mensaje): ?>
            <p class="error"><?php echo $mensaje; ?></p>
        <?php endif; ?>
        <form method="post">
            <input type="email" name="email" placeholder="Correo electrónico" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <button type="submit">Entrar</button>
            <button type="button" onclick="history.back()">⬅ Volver</button>
        </form>
        <p>¿No tienes cuenta? <a href="registro.php">Regístrate aquí</a></p>
    </div>
</body>
</html>
