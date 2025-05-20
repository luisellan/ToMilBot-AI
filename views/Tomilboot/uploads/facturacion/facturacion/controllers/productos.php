<?php
include_once("../config/conexion.php");
include_once("../models/Productos.php");

$productos = new Productos();

switch ($_GET["op"]) {

    case "listar":
        $datos = $productos->listarProductos();
        $data = array();
        foreach ($datos as $row) {
            $sub_array = array();
            $sub_array[] = $row["PROD_NOM"];
            $sub_array[] = $row["PROD_DESCRIPCION"];
            $sub_array[] = $row["PROD_PRECIO"];
            $sub_array[] = $row["PROD_STOCK"];
            $sub_array[] = $row["ESTADO"];
            $sub_array[] = $row["FECH_CREA"];
            $sub_array[] = '<button type="button" onClick="editar(' . $row["PROD_ID"] . ')" id="' . $row["PROD_ID"] . '" class="btn btn-warning btn-icon waves-effect waves-light"><i class="ri-edit-2-line"></i></button>';
            $sub_array[] = '<button type="button" onClick="eliminar(' . $row["PROD_ID"] . ')" id="' . $row["PROD_ID"] . '" class="btn btn-danger btn-icon waves-effect waves-light"><i class="ri-delete-bin-5-line"></i></button>';
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
        $datos = $productos->producto_id($_POST["prod_id"]);
        if (is_array($datos) == true and count($datos) > 0) {
            foreach ($datos as $row) {
                $output["PROD_ID"] = $row["PROD_ID"];
                $output["PROD_NOM"] = $row["PROD_NOM"];
                $output["PROD_PRECIO"] = $row["PROD_PRECIO"];
                $output["PROD_STOCK"] = $row["PROD_STOCK"];
            }
            echo json_encode($output);
        }
        break;

    case "combo";
        $datos = $productos->listarProductos();
        if (is_array($datos) == true and count($datos) > 0) {
            $html = "";
            $html .= "<option selected>Seleccionar</option>";
            foreach ($datos as $row) {
                $html .= "<option value='" . $row["PROD_ID"] . "'>" . $row["PROD_NOM"] . "</option>";
            }
            echo $html;
        }
        break;
}
