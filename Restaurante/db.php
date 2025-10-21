<?php
$servername = "PMYSQL192.dns-servicio.com:3306";
$username = "11129149_platos";
$password = "RmEmDFA_d5pBQqC."; // pon aquí la contraseña correcta
$dbname = "11129149_platos"; // pon aquí el nombre de tu base de datos exacto

$conn = new mysqli($servername, $username, $password, $dbname);

// Forzar UTF-8
$conn->set_charset("utf8mb4");
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
