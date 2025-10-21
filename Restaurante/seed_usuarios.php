<?php
include "db.php";

// Listas de nombres y apellidos
$nombres = ["Carlos", "María", "Lucía", "José", "Laura", "Miguel", "Ana", "David", "Elena", "Javier"];
$apellidos = ["Gómez", "Pérez", "López", "Martínez", "Rodríguez", "Fernández", "Ruiz", "Sánchez", "Díaz", "Vargas"];

for ($i = 1; $i <= 10; $i++) {
    $nombre = $nombres[array_rand($nombres)] . " " . $apellidos[array_rand($apellidos)];
    $email = strtolower(str_replace(" ", ".", $nombre)) . "@ejemplo.com";
    $password = "1234"; // Contraseña simple para pruebas

    $sql = "INSERT INTO usuarios (nombre, email, password) 
            VALUES ('$nombre', '$email', '$password')";
    $conn->query($sql);
}

echo "✅ 10 usuarios aleatorios generados correctamente.";
?>
<?php   