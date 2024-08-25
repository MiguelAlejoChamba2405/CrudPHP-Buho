<?php
$servername = "localhost";
$database = "ejercicio_2";
$username = "root";
$password = "";

// Crear la conexión usando mysqli
$conn = new mysqli($servername, $username, $password, $database);
$conn -> set_charset("utf8");
// Verificar la conexión
if ($conn->connect_error) { // Correcto: Verificar si hay un error en la conexión
    die("FALLO: " . $conn->connect_error);
}
// La conexión permanece abierta para ser utilizada en otras partes del código
?>


