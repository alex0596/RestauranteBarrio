<?php
session_start();
include "db.php";

// Orden por defecto
$orden = "nombre ASC";
if (isset($_GET['orden'])) {
    switch ($_GET['orden']) {
        case "precio_asc":
            $orden = "precio ASC";
            break;
        case "precio_desc":
            $orden = "precio DESC";
            break;
        case "valoracion_desc":
            $orden = "valoracion_media DESC";
            break;
        case "valoracion_asc":
            $orden = "valoracion_media ASC";
            break;
        default:
            $orden = "nombre ASC";
    }
}

// Obtener lista de platos con el orden seleccionado
$sql = "
SELECT p.*, AVG(c.valoracion) AS valoracion_media
FROM platos p
LEFT JOIN comentarios c ON p.id = c.plato_id
GROUP BY p.id
ORDER BY $orden
";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Content-Security-Policy" content="default-src 'self' https:;">
    <meta http-equiv="X-Content-Type-Options" content="nosniff">
    <meta http-equiv="X-Frame-Options" content="DENY">
    <meta name="referrer" content="no-referrer">
    <meta charset="UTF-8">
    <title>Nuestra Carta</title>
    <link rel="icon" href="imagenes/favicon.png" type="image/png">
    <link rel="stylesheet" href="style1.css">
</head>
<body class="tema-rojo" id="bodyPlastos">

<header>
    <h1>🍽️ Nuestra Carta</h1>
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

<main class="main-content">
    <div class="container" id="fondo">
        <h2 style="text-align:center; margin-bottom:25px;">Descubre nuestros platos</h2>

        <!-- Filtros -->
        <div style="display:flex; justify-content: space-between; flex-wrap: wrap; margin-bottom:20px; gap:10px;">
            <!-- Barra de búsqueda -->
            <input type="text" id="filtro" placeholder="Buscar plato..." onkeyup="filtrarPlatos()" style="flex:1; min-width:200px;">

            <!-- Selector de orden -->
            <form method="GET" style="flex:0;">
                <select name="orden" onchange="this.form.submit()">
                    <option value="nombre" <?php if($_GET['orden'] ?? '' == 'nombre') echo 'selected'; ?>>Ordenar por nombre</option>
                    <option value="precio_asc" <?php if($_GET['orden'] ?? '' == 'precio_asc') echo 'selected'; ?>>Precio: Menor a Mayor</option>
                    <option value="precio_desc" <?php if($_GET['orden'] ?? '' == 'precio_desc') echo 'selected'; ?>>Precio: Mayor a Menor</option>
                    <option value="valoracion_desc" <?php if($_GET['orden'] ?? '' == 'valoracion_desc') echo 'selected'; ?>>Mejor Valorados</option>
                    <option value="valoracion_asc" <?php if($_GET['orden'] ?? '' == 'valoracion_asc') echo 'selected'; ?>>Peor Valorados</option>
                </select>
            </form>
        </div>

        <!-- Grid de platos -->
        <div class="grid" id="lista-platos">
            <?php while($row = $result->fetch_assoc()): ?>
                <div class="card">
                    <img src="imagenes/<?php echo $row['imagen']; ?>" alt="<?php echo $row['nombre']; ?>">
                    <div class="card-body">
                        <h2><?php echo $row['nombre']; ?></h2>
                        <p><?php echo $row['descripcion']; ?></p>
                        <p class="precio"><?php echo number_format($row['precio'], 2); ?> €</p>
                        <p class="valoracion">
                            ⭐ <?php echo $row['valoracion_media'] !== null ? number_format($row['valoracion_media'], 1) : "Sin valoraciones"; ?>
                        </p>
                        <a href="plato.php?id=<?php echo $row['id']; ?>" class="btn">Ver detalles</a>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</main>

<!-- FOOTER -->
<footer id="footerPlatos">
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

function filtrarPlatos(){
    const input = document.getElementById('filtro').value.toLowerCase();
    const platos = document.querySelectorAll("#lista-platos .card");

    platos.forEach(plato => {
        const nombre = plato.querySelector("h2").textContent.toLowerCase();
        plato.style.display = nombre.includes(input) ? "block" : "none";
    });
}
</script>

</body>
</html>
