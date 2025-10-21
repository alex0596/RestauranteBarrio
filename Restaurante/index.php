<?php
session_start();
include "db.php";

// Comentario destacado
$sql = "SELECT c.comentario, c.valoracion, c.fecha, u.nombre, p.nombre AS plato
        FROM comentarios c
        JOIN usuarios u ON c.usuario_id = u.id
        JOIN platos p ON c.plato_id = p.id
        ORDER BY c.valoracion DESC, c.fecha DESC
        LIMIT 1";
$resultado = $conn->query($sql);
$comentario_destacado = $resultado->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Content-Security-Policy" content="default-src 'self' https:;">
	<meta http-equiv="X-Content-Type-Options" content="nosniff">
	<meta http-equiv="X-Frame-Options" content="DENY">
	<meta name="referrer" content="no-referrer">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurante de Barrio</title>
    <link rel="stylesheet" href="style1.css">
    <link rel="icon" href="imagenes/favicon.png" type="image/png">
</head>
<body>
<header>
    <h1>🍽️ Restaurante de Barrio</h1>
    <nav>
        <a href="index.php">Inicio</a>
        <a href="platos.php">Platos</a>
        <?php if(isset($_SESSION['usuario_id'])): ?>
            <a href="perfil.php?id=<?php echo $_SESSION['usuario_id']; ?>">Perfil</a>
            <a href="logout.php">Cerrar sesión</a>
        <?php else: ?>
            <a href="login.php">Iniciar sesión</a>
            <a href="registro.php">Registrarse</a>
        <?php endif; ?>
    </nav>

    <!-- Selector de tema -->
    <label for="selector-tema">Tema:</label>
    <select id="selector-tema" onchange="cambiarTema()">
        <option value="tema-rojo">Rojo</option>
        <option value="tema-verde">Verde</option>
        <option value="tema-azul">Azul</option>
    </select>
</header>

<div class="form-container">
    <h2>Bienvenido a nuestro restaurante 🍴</h2>
    <div class="imagenes-inicio">
        <img src="imagenes/restaurante.webp" alt="Restaurante">
        <img src="imagenes/paella.webp" alt="Paella">
        <img src="imagenes/pizza.webp" alt="Pizza">
        <img src="imagenes/tortilla.webp" alt="Tortilla">
    </div>

    <hr>
    <h2>⭐ Comentario Destacado</h2>
    <?php if($comentario_destacado): ?>
        <div class="comentario">
            <p>"<?php echo $comentario_destacado['comentario']; ?>"</p>
            <p>— <strong><?php echo $comentario_destacado['nombre']; ?></strong>, 
               sobre <em><?php echo $comentario_destacado['plato']; ?></em></p>
            <p>Valoración: <?php echo $comentario_destacado['valoracion']; ?>/5 ⭐</p>
            <small><?php echo $comentario_destacado['fecha']; ?></small>
        </div>
    <?php else: ?>
        <p>No hay comentarios todavía.</p>
    <?php endif; ?>
</div>

<footer id="footerGeneral">
    <div class="footer-container">
        <!-- Columna 1: Contacto -->
        <div class="footer-col">
            <h3>📍 Contacto</h3>
            <p>Paseo Marítimo, 45<br>29640 Fuengirola, Málaga<br>España</p>
            <p>📞 +34 952 123 456</p>
            <p>✉️ info@saboresdelmar.es</p>
            <p>🌐 www.saboresdelmar.es</p>
        </div>

        <!-- Columna 2: Horarios -->
        <div class="footer-col">
            <h3>🕒 Horarios</h3>
            <ul class="hours-list">
                <li>Lunes - Jueves: 12:00 - 23:00</li>
                <li>Viernes - Sábado: 12:00 - 00:00</li>
                <li>Domingo: 12:00 - 23:00</li>
                <li>Cocina cierra: 22:30</li>
            </ul>
        </div>

        <!-- Columna 3: Legales -->
        <div class="footer-col">
            <h3>⚖️ Información</h3>
            <ul class="footer-links">
                <li><a href="#privacy">Política de Privacidad</a></li>
                <li><a href="#terms">Términos y Condiciones</a></li>
                <li><a href="#cookies">Política de Cookies</a></li>
            </ul>
        </div>
    </div>

    <!-- Línea inferior -->
    <div class="footer-bottom">
        <p>&copy; 2024 Sabores del Mar. Todos los derechos reservados.</p>
    </div>
</footer>

<script>
document.body.className = "tema-rojo"; // Tema inicial

function cambiarTema(){
    const tema = document.getElementById('selector-tema').value;
    document.body.className = tema;
}
</script>
</body>
</html>
