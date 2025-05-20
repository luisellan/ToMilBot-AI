<?php
include "../../models/conexion.php"; // Asegúrate de que la ruta a este archivo es correcta

// Realizamos la consulta a la base de datos
$sql = $conexion->query("SELECT * FROM productos");

$productos = array();

// Recorremos el resultado de la consulta y lo agregamos al arreglo $productos
while ($producto = $sql->fetch_assoc()) {
    $productos[] = $producto;
}

// Devolvemos los datos en formato JSON
echo json_encode($productos);
?>
