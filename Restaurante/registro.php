<?php
include "db.php";

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validar si ya existe el usuario
    $check = $conn->query("SELECT * FROM usuarios WHERE email='$email'");
    if ($check->num_rows > 0) {
        $mensaje = "⚠️ Este email ya está registrado";
    } else {
        $sql = "INSERT INTO usuarios (nombre, email, password) VALUES ('$nombre', '$email', '$password')";
        if ($conn->query($sql)) {
            $mensaje = "✅ Registro exitoso, ahora puedes iniciar sesión";
        } else {
            $mensaje = "❌ Error al registrar usuario";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registro</title>
    <link rel="stylesheet" href="stylesLogin.css">
    <link rel="icon" href="imagenes/favicon.png" type="image/png">
</head>
<body class="tema-rojo">
    <div class="form-container">
        <h1>📝 Registro</h1>
        <?php if ($mensaje): ?>
            <p class="error"><?php echo $mensaje; ?></p>
        <?php endif; ?>
        <form method="post">
            <input type="text" name="nombre" placeholder="Nombre completo" required>
            <input type="email" name="email" placeholder="Correo electrónico" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <button type="submit">Registrarse</button>
            <button type="button" onclick="history.back()">⬅ Volver</button>

        </form>
        <p>¿Ya tienes cuenta? <a href="login.php">Inicia sesión</a></p>
    </div>
</body>
</html>
