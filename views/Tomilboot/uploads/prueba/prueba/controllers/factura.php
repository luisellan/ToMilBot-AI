<?php
include_once("../config/conexion.php");
require_once("../models/Factura.php");
$factura = new Factura();


if (isset($_GET['op'])) {
    $op = $_GET['op'];

    switch ($op) {
        case "listar":
            $datos = $factura->get_Factura();
            $data = array();
            foreach ($datos as $row) {
                $sub_array = array();

                $sub_array[] = $row["CLI_NOM"];
                $sub_array[] = "$" . number_format($row["Total"], 2);
                $sub_array[] = "$" . number_format($row["subtotal"], 2);
                $sub_array[] = $row["Fecha"];
                $sub_array[] = '<button type="button" onClick="editar(' . $row["id"] . ')" id="' . $row["id"] . '" class="btn btn-warning btn-icon waves-effect waves-light"><i class="ri-edit-line"></i></button>';
                $sub_array[] = '<a href="factura.php?v=' . $row["id"] . '" target="_blank" class="btn btn-primary btn-icon waves-effect waves-light"><i class="ri-printer-line"></i></a>';


                $data[] = $sub_array;
            }

            $results = array(
                "sEcho" => 1,
                "iTotalRecords" => count($data),
                "iTotalDisplayRecords" => count($data),
                "aaData" => $data
            );
            echo json_encode($results);
            break;



        case "listar_items":
            $datos = $factura->get_Factura_id($_POST["id"]);
            $data = array();
            foreach ($datos as $row) {
                $sub_array = array();
                $sub_array[] = $row["factura_id"];
                $sub_array[] = $row["CLI_NOM"];
                $sub_array[] = $row["producto"];
                $sub_array[] = "$" . number_format($row["precio"], 2);
                $sub_array[] = $row["cantidad"];
                $sub_array[] = "0" . $row["iva"] . "%";
                $sub_array[] = "$" . number_format($row["subtotal"], 2);
                $sub_array[] = "$" . number_format($row["monto_iva"], 2);
                $sub_array[] = "$" . number_format($row["total"], 2);

                $data[] = $sub_array;
            }

            $results = array(
                "sEcho" => 1,
                "iTotalRecords" => count($data),
                "iTotalDisplayRecords" => count($data),
                "aaData" => $data
            );
            echo json_encode($results);
            break;



        case "mostrar":
            $datos = $factura->get_Factura_id($_POST["id"]);
            if (is_array($datos) == true and count($datos) > 0) {
                foreach ($datos as $row) {
                    $output["factura_id"] = $row["factura_id"];
                    $output["id"] = $row["id"];
                    $output["Fecha"] = $row["Fecha"];
                    $output["CLI_NOM"] = $row["CLI_NOM"];
                    $output["producto"] = $row["producto"];
                    $output["precio"] = $row["precio"];
                    $output["subtotal"] = $row["subtotal"];
                    $output["monto_iva"] = $row["monto_iva"];
                    $output["total"] = $row["total"];
                    $output["Total"] = $row["Total"];
                    
                    $output["CLI_CORREO"] = $row["CLI_CORREO"];
                    $output["CLI_DIRECCION"] = $row["CLI_DIRECCION"];
                    $output["CLI_TELEFONO"] = $row["CLI_TELEFONO"];
                }
                echo json_encode($output);
            }
            break;



        case "listartopproducto":

            $datos = $factura->get_Factura_id($_POST["id"]);
            $data = array();

            foreach ($datos as $row) {
?>
                <tr>
                    <td>
                        <h5 class="fs-14 my-1 fw-normal"><?php echo $row['producto']; ?></h5>
                        <span class="text-muted">Producto</span>
                    </td>
                    <td>
                        <h5 class="fs-14 my-1 fw-normal"><?php echo $row['CLI_NOM']; ?></h5>
                        <span class="text-muted">Cliente</span>
                    </td>
                    <td>
                        <h5 class="fs-14 my-1 fw-normal"><?php echo $row['precio']; ?></h5>
                        <span class="text-muted">Precio</span>
                    </td>
                    <td>
                        <h5 class="fs-14 my-1 fw-normal"><?php echo $row['cantidad']; ?></h5>
                        <span class="text-muted">Cantidad</span>
                    </td>
                    
                    <td>
                        <h5 class="fs-14 my-1 fw-normal">0<?php echo $row['iva']; ?></h5>
                        <span class="text-muted">IVA</span>
                    </td>
                </tr>
<?php
                // Agregar datos al array para el JSON
                $data[] = array(
                    "factura_id" => $row["factura_id"],
                    "subtotal" => $row["subtotal"],
                    "monto_iva" => $row["monto_iva"]
                );
            }

            // Retornar datos en formato JSON
            $results = array(
                "sEcho" => 1,
                "iTotalRecords" => count($data),
                "iTotalDisplayRecords" => count($data),
                "aaData" => $data
            );
            echo json_encode($results);

            break;






        case "guardar":

            if (isset($_POST['factura'])) {

                $facturaData = json_decode($_POST['factura'], true);


                $facturaModel = new Factura();

                try {
                    $resultado = $facturaModel->guardarFactura($facturaData);

                    if ($resultado) {

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
                } catch (PDOException $e) {

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
