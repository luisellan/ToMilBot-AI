<?php
include "../../models/conexion.php"; // Asegúrate de que la ruta a este archivo es correcta
$data = json_decode(file_get_contents('php://input'), true);

$nombre = $data['nombre'];
$cantidad = $data['cantidad'];
$precio = $data['precio'];

$sql = "INSERT INTO productos (nombre, cantidad, precio) VALUES ('$nombre', '$cantidad', '$precio')";

if ($conexion->query($sql) == true) {
    echo json_encode(["message" => "Producto guardado exitosamente"]);
} else {
    echo json_encode(["message" => "Error al guardar producto"]);
}

?>
