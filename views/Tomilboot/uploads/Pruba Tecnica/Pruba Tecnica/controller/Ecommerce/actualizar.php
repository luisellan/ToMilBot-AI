<?php
include "../../models/conexion.php"; // Asegúrate de que la ruta a este archivo es correcta
$data = json_decode(file_get_contents('php://input'), true);

$id = $data['id'];
$nombre = $data['nombre'];
$cantidad = $data['cantidad'];
$precio = $data['precio'];

$sql = "UPDATE productos SET nombre='$nombre', cantidad='$cantidad', precio='$precio' WHERE id=$id";

if ($conexion->query($sql) == true) {
    echo json_encode(["message" => "Producto actualizado exitosamente"]);
} else {
    echo json_encode(["message" => "Error al actualizado producto"]);
}

?>
