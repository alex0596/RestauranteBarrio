<?php
session_start();
include "db.php";

// Obtener ID del usuario
if (isset($_GET['id'])) {
    $usuario_id = intval($_GET['id']);
} elseif (isset($_SESSION['usuario_id'])) {
    $usuario_id = $_SESSION['usuario_id'];
} else {
    die("Usuario no especificado.");
}

// Obtener información del usuario
$sql = "SELECT * FROM usuarios WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$usuario = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Obtener comentarios del último mes
$sql = "SELECT c.comentario, c.valoracion, c.fecha, p.nombre AS plato
        FROM comentarios c
        JOIN platos p ON c.plato_id = p.id
        WHERE c.usuario_id = ? AND c.fecha >= DATE_SUB(NOW(), INTERVAL 1 MONTH)
        ORDER BY c.fecha DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$comentarios = $stmt->get_result();
$stmt->close();

// Calcular descuento acumulado (5% por cada valoración >=2)
$sql = "SELECT COUNT(*) AS cnt FROM comentarios WHERE usuario_id = ? AND valoracion >= 2";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$res = $stmt->get_result()->fetch_assoc();
$stmt->close();

$descuento_total = $res['cnt'] * 5;
?>

<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Security-Policy" content="default-src 'self' https:;">
    <meta http-equiv="X-Content-Type-Options" content="nosniff">
    <meta http-equiv="X-Frame-Options" content="DENY">
    <meta name="referrer" content="no-referrer">
    <title>Perfil de <?php echo $usuario['nombre']; ?></title>
    <link rel="icon" href="imagenes/favicon.png" type="image/png">
    <link rel="stylesheet" href="style1.css">
</head>
<body class="tema-rojo">

<header>
    <h1>Perfil de <?php echo $usuario['nombre']; ?></h1>
    <nav>
        <a href="index.php">Inicio</a>
        <a href="platos.php">Platos</a>
        <?php if (isset($_SESSION['usuario_id'])): ?>
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

<div class="container">
    <h2>Descuento acumulado</h2>
    <?php if($descuento_total > 0): ?>
        <div class="descuento-mensaje">
            🎉 ¡Has acumulado un <strong><?php echo $descuento_total; ?>%</strong> de descuento gracias a tus valoraciones positivas!
        </div>
    <?php else: ?>
        <p>No tienes descuentos acumulados todavía. ¡Deja valoraciones para ganar descuentos! ⭐</p>
    <?php endif; ?>

    <hr>

    <h2>Comentarios del último mes</h2>
    <?php if($comentarios->num_rows > 0): ?>
        <?php while($row = $comentarios->fetch_assoc()): ?>
            <div class="comentario">
                <strong><?php echo $row['plato']; ?></strong> (<?php echo $row['valoracion']; ?>/5 ⭐)
                <p><?php echo $row['comentario']; ?></p>
                <small><?php echo $row['fecha']; ?></small>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No hay comentarios en el último mes.</p>
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
function cambiarTema(){
    const tema = document.getElementById('selector-tema').value;
    document.body.className = tema;
}
</script>

</body>
</html>
<?php