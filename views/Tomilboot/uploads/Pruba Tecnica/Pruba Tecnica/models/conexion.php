<?php
$host = "localhost";
$usuario = "root";
$password = "";
$base_datos = "crud_php";

// Crear conexión
$conexion = new mysqli($host, $usuario, $password, $base_datos);

// Verificar errores de conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Establecer conjunto de caracteres a UTF-8
if (!$conexion->set_charset("utf8mb4")) {
    die("Error al establecer el conjunto de caracteres: " . $conexion->error);
}

// Opcional: mensaje de éxito (solo en desarrollo)
/* echo "✅ Conexión exitosa a la base de datos."; */
?>
