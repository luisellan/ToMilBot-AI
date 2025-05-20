<?php

require_once("../models/Factura.php");

if(isset($_GET['op'])){
    $op = $_GET['op'];

    switch($op){
        case "guardar":
            // Verifica si se recibió el parámetro 'factura' vía POST
            if(isset($_POST['factura'])){
                // Decodifica el JSON recibido
                $facturaData = json_decode($_POST['factura'], true);
                
                // Opcional: valida la estructura de $facturaData según tus necesidades
                
                // Instancia el modelo y llama al método para guardar la factura
                $facturaModel = new Factura();
                
                try {
                    $resultado = $facturaModel->guardarFactura($facturaData);
                    
                    if($resultado){
                        // Responde con un mensaje de éxito
                        echo json_encode([
                            "status" => "success",
                            "message" => "Factura guardada correctamente."
                        ]);
                    } else {
                        echo json_encode([
                            "status" => "error",
                            "message" => "No se pudo guardar la factura."
                        ]);
                    }
                } catch(PDOException $e) {
                    // En caso de error, envía el mensaje de error
                    echo json_encode([
                        "status" => "error",
                        "message" => "Error al guardar la factura: " . $e->getMessage()
                    ]);
                }
            } else {
                echo json_encode([
                    "status" => "error",
                    "message" => "No se recibieron datos de la factura."
                ]);
            }
            break;
            
        default:
            echo json_encode([
                "status" => "error",
                "message" => "Operación no reconocida."
            ]);
            break;
    }
} else {
    echo json_encode([
        "status" => "error",
        "message" => "No se especificó ninguna operación."
    ]);
}
?>
