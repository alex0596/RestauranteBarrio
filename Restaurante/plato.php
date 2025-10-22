<?php
session_start();
include "db.php";

if (!isset($_GET['id'])) {
    die("Plato no especificado.");
}
$plato_id = intval($_GET['id']);

// Obtener info del plato
$sql = "SELECT * FROM platos WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $plato_id);
$stmt->execute();
$plato = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Insertar comentario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['usuario_id'])) {
    $comentario = $_POST['comentario'];
    $valoracion = intval($_POST['valoracion']);
    $usuario_id = $_SESSION['usuario_id'];

    $sql = "INSERT INTO comentarios (usuario_id, plato_id, comentario, valoracion) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iisi", $usuario_id, $plato_id, $comentario, $valoracion);
    $stmt->execute();
    $stmt->close();

    // Redirigir pasando la valoración para mostrar mensaje de descuento
    header("Location: plato.php?id=$plato_id&valoracion=$valoracion");
    exit;
}

// Obtener comentarios
$sql = "SELECT c.comentario, c.valoracion, c.fecha, u.nombre, u.id as usuario_id
        FROM comentarios c
        JOIN usuarios u ON c.usuario_id = u.id
        WHERE c.plato_id = ?
        ORDER BY c.fecha DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $plato_id);
$stmt->execute();
$comentarios = $stmt->get_result();
$stmt->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo $plato['nombre']; ?></title>
    <link rel="stylesheet" href="style1.css">
    <link rel="icon" href="imagenes/favicon.png" type="image/png">
</head>
<body class="tema-rojo">

<header>
    <h1><?php echo $plato['nombre']; ?></h1>
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

    <!-- Información del plato -->
    <div class="card card-plato">
        <img src="imagenes/<?php echo $plato['imagen']; ?>" alt="<?php echo $plato['nombre']; ?>">
        <div class="card-body">
            <h2><?php echo $plato['nombre']; ?></h2>
            <p><?php echo $plato['descripcion']; ?></p>
            <p class="precio"><?php echo $plato['precio']; ?> €</p>
        </div>
    </div>

    <!-- Mensaje de descuento -->
    <?php
    // Descuento individual por última valoración
    if (isset($_GET['valoracion'])) {
        $valoracion_usuario = intval($_GET['valoracion']);
        if ($valoracion_usuario >= 2) {
            echo '<div class="descuento-mensaje">🎉 ¡Gracias por tu valoración! Tienes un <strong>5% de descuento</strong> en tu próximo pedido de este plato.</div>';
        }
    }

    // Descuento acumulado por todas las valoraciones >=4
    if (isset($_SESSION['usuario_id'])) {
        $usuario_id = $_SESSION['usuario_id'];

        $sql = "SELECT COUNT(*) as cnt FROM comentarios WHERE usuario_id = ? AND valoracion >= 4";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $usuario_id);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        $descuento_total = $res['cnt'] * 2; // 5% por cada valoración positiva

        if($descuento_total > 0){
            echo '<div class="descuento-mensaje">🎉 ¡Tienes un descuento acumulado del <strong>' 
                 . $descuento_total . '%</strong> gracias a tus valoraciones positivas!</div>';
        }
    }
    ?>

    <hr>

    <!-- Comentarios -->
    <h2>Comentarios</h2>
    <?php while($row = $comentarios->fetch_assoc()): ?>
        <div class="comentario">
            <strong>
                <a href="perfil.php?id=<?php echo $row['usuario_id']; ?>">
                    <?php echo $row['nombre']; ?>
                </a>
            </strong> 
            (<?php echo $row['valoracion']; ?>/5 ⭐)  
            <p><?php echo $row['comentario']; ?></p>
            <small><?php echo $row['fecha']; ?></small>
        </div>
    <?php endwhile; ?>

    <!-- Formulario para nuevo comentario -->
    <?php if (isset($_SESSION['usuario_id'])): ?>
        <div class="form-container">
            <h3>Deja tu opinión</h3>
            <form method="POST">
                <label>Valoración (1-5):</label>
                <input type="number" name="valoracion" min="1" max="5" required>
                <textarea name="comentario" placeholder="Escribe tu opinión..." required></textarea>
                <button type="submit">Enviar</button>
            </form>
        </div>
    <?php else: ?>
        <p><a href="login.php">Inicia sesión</a> para dejar un comentario.</p>
    <?php endif; ?>

</div>

<footer>
    <p>© 2025 Restaurante de Barrio</p>
</footer>

<script>
function cambiarTema(){
    const tema = document.getElementById('selector-tema').value;
    document.body.className = tema;
}
</script>

</body>
</html>
