<?php
include_once("../config/conexion.php");
include_once("../models/Cliente.php");

$cliente = new Cliente();

switch ($_GET["op"]) {
    case "listar":
        $datos = $cliente->get_clientes();
        $data = array();
        foreach ($datos as $row) {
            $sub_array = array();

            $sub_array[] = $row["CLI_NOM"];
            $sub_array[] = $row["CLI_DESCRIP"];
            $sub_array[] = $row["CLI_PRECIO"];
            $sub_array[] = $row["CLI_STOCK"];
            $sub_array[] = $row["IVA"];
            $sub_array[] = $row["ESTADO"];
            $sub_array[] = $row["FECH_CREA"];
            $sub_array[] = '<button type="button" onClick="editar(' . $row["CLI_ID"] . ')" id="' . $row["CLI_ID"] . '" class="btn btn-warning btn-icon waves-effect waves-light"><i class="ri-edit-2-line"></i></button>';
            $sub_array[] = '<button type="button" onClick="eliminar(' . $row["CLI_ID"] . ')" id="' . $row["CLI_ID"] . '" class="btn btn-danger btn-icon waves-effect waves-light"><i class="ri-delete-bin-5-line"></i></button>';
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
        $datos = $cliente->get_cliente_id($_POST["cli_id"]);
        if (is_array($datos) == true and count($datos) > 0) {
            foreach ($datos as $row) {
                $output["CLI_ID"] = $row["CLI_ID"];
                $output["CLI_NOM"] = $row["CLI_NOM"];
                $output["CLI_CORREO"] = $row["CLI_CORREO"];
                $output["CLI_DIRECCION"] = $row["CLI_DIRECCION"];
                $output["CLI_TELEFONO"] = $row["CLI_TELEFONO"];
            }
            echo json_encode($output);
        }
        break;

    case "combo";
        $datos = $cliente->get_clientes();
        if (is_array($datos) == true and count($datos) > 0) {
            $html = "";
            $html .= "<option selected>Seleccionar</option>";
            foreach ($datos as $row) {
                $html .= "<option value='" . $row["CLI_ID"] . "'>" . $row["CLI_NOM"] . "</option>";
            }
            echo $html;
        }
        break;
}
