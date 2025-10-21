<?php
$servername = "sql205.infinityfree.com";
$username = "if0_39953506";
$password = "6E1fL68rhnJDlSX"; // pon aquí la contraseña correcta
$dbname = "if0_39953506_bar"; // pon aquí el nombre de tu base de datos exacto

$conn = new mysqli($servername, $username, $password, $dbname);

// Forzar UTF-8
$conn->set_charset("utf8mb4");
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
