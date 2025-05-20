<?php
include "../../models/conexion.php"; // Asegúrate de que la ruta a este archivo es correcta

$id = $_GET["id"];
// Realizamos la consulta a la base de datos
$sql = $conexion->query("SELECT * FROM productos where $id");

$producto = $sql->fetch_assoc();
   

// Devolvemos los datos en formato JSON
echo json_encode($producto);
?>
