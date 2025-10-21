<?php
include "db.php";

// Frases de prueba
$comentarios = [
    "¡Estaba delicioso, repetiré sin duda!",
    "La presentación fue excelente, pero el sabor regular.",
    "Muy buena relación calidad-precio.",
    "Un poco salado para mi gusto, pero bien cocinado.",
    "El mejor plato que he probado en mucho tiempo.",
    "Lo recomiendo totalmente, auténtico sabor casero.",
    "Ración generosa y bien servida.",
    "El servicio fue rápido y amable.",
    "El plato estaba correcto, aunque faltaba un poco de sazón.",
    "Simplemente espectacular, ¡5 estrellas!"
];

// Obtener todos los platos
$sql_platos = "SELECT id FROM platos";
$resultado = $conn->query($sql_platos);

// Obtener IDs de usuarios
$usuarios = [];
$result_usuarios = $conn->query("SELECT id FROM usuarios");
while($row = $result_usuarios->fetch_assoc()){
    $usuarios[] = $row['id'];
}

if ($resultado->num_rows > 0 && count($usuarios) > 0) {
    while ($plato = $resultado->fetch_assoc()) {
        $plato_id = $plato['id'];
        $num_comentarios = rand(3, 6);

        for ($i = 0; $i < $num_comentarios; $i++) {
            $usuario_id = $usuarios[array_rand($usuarios)];
            $comentario = $comentarios[array_rand($comentarios)];
            $valoracion = rand(1, 5);
            $fecha = date("Y-m-d H:i:s", strtotime("-" . rand(0,30) . " days"));

            $sql_insert = "INSERT INTO comentarios (usuario_id, plato_id, comentario, valoracion, fecha)
                           VALUES ('$usuario_id', '$plato_id', '$comentario', '$valoracion', '$fecha')";
            $conn->query($sql_insert);
        }
    }
    echo "✅ Comentarios aleatorios generados correctamente.";
} else {
    echo "⚠️ No hay platos o usuarios en la base de datos.";
}
