<?php
include "../../models/conexion.php"; // Asegúrate de que la ruta a este archivo es correcta

$data = json_decode(file_get_contents('php://input'), true);

$id = $data['id'];

$sql = "DELETE FROM productos where id = $id";
if ($conexion->query($sql) == true) {
    echo json_encode(["message" => "Producto eliminado exitosamente"]);
} else {
    echo json_encode(["message" => "Error al eliminado producto"]);
}
?>
