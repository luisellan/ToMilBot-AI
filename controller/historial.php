<?php

/*  TODO: LLAMANDO CLASES */
require_once("../config/conexion.php");

require_once("../models/Historial.php");
/* TODO: INICIALIZANDO CLASES */
// Creando una nueva instancia (objeto) de la clase Rol. 
$historial = new Historial();


switch ($_GET["op"]) {

    case "listar":
        $datos = $historial->listar($_POST["user_idx"]);
        $data = array();
        foreach ($datos as $row) {
            $sub_array = array();
            $sub_array[] = $row["zip_id"];
            $sub_array[] = $row["zip_name"];
            $sub_array[] = '<a href="../Tomilboot/uploads/' . $row["zip_name"] . '" class="btn btn-success" download>Descargar ZIP</a>';
            $sub_array[] = '<a href="../Tomilboot/uploads/corregido_' . $row["zip_name"] . '" class="btn btn-warning" download>Descargar Corregido</a>';

            $sub_array[] = $row["upload_date"];

            $sub_array[] = '<a href="vulnerabilidad.php?id_zip=' . $row["zip_id"] . '" class="btn btn-primary btn-icon waves-effect waves-light">
                    <i class="ri-settings-2-line"></i>
                </a>';
            $data[] = $sub_array;
        }
        // necesitamos este codigo paracontar cuantos informacion hay

        $results = array(
            "sEcho" => 1,
            "iTotalRecords" => count($data),
            "iTotalDisplayRecorsd" => count($data),
            "aaData" => $data
        );
        echo json_encode($results);
        break;
    /*    TODO: MOSTRAR INFORMACION DE REGISTROS SEGUN SU ID */
    case "mostrar":
        // Llamas a la función obtener() pasándole el id_zip
        $datos = $historial->obtener($_POST["id_zip"],$_POST["usu_id"]);

        // Verificas si obtuviste datos
        if ($datos !== false && is_array($datos)) {
            // Iteras a través de los datos obtenidos y los muestras
            foreach ($datos as $dato) {
                echo "ID ZIP: " . $dato["zip_id"] . "<br>";
                echo "ZIP Name: " . $dato["zip_name"] . "<br>";
                echo "Upload Date: " . $dato["upload_date"] . "<br>";
                echo "Vul ID: " . $dato["vul_id"] . "<br>";
                echo "Archivo: " . $dato["archivo"] . "<br>";
                echo "Tipo Vulnerabilidad: " . $dato["tipo_vulnerabilidad"] . "<br>";
                echo "Código Vulnerable: " . $dato["codigo_vulnerable"] . "<br>";
                echo "Solución Propuesta: " . $dato["solucion_propuesta"] . "<br>";
                echo "Fecha Subida: " . $dato["fecha_subida"] . "<br>";
                echo "<hr>";
            }
        } else {
            echo json_encode(["error" => "Datos no encontrados"]);
        }
        break;
}
