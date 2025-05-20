<?php

/*  TODO: LLAMANDO CLASES */
require_once("../config/conexion.php");

require_once("../models/Rol.php");
/* TODO: INICIALIZANDO CLASES */
// Creando una nueva instancia (objeto) de la clase Rol. 
$rol = new Rol();


switch ($_GET["op"]) {
    /* TODO: Guardar cuando el ID este vacio y editar cunaod se envie el ID */
    case "guardaryeditar":
        if (empty($_POST["rol_id"])) {
            $rol->insertar($_POST["rol_nom"]);
        } else {
            $rol->modificar($_POST["rol_id"], $_POST["rol_nom"]);
        }
        break;
    /*    TODO: LISTADO DE REGISTROS FORMATO JSON PARA DATATABLE JS */
    case "listar":
        $datos = $rol->listar();
        $data = array();
        foreach ($datos as $row) {
            $sub_array = array();
            $sub_array[] = $row["ROL_NOM"];
            $sub_array[] = $row["FECH_CREA"];
            $sub_array[] = '<button type="button" onClick="permisos(' . $row["ROL_ID"] . ')" id="' . $row["ROL_ID"] . '" class="btn btn-primary btn-icon waves-effect waves-light"><i class="ri-settings-2-line"></i></button>';
            $sub_array[] = '<button type="button" onClick="editar(' . $row["ROL_ID"] . ')" id="' . $row["ROL_ID"] . '" class="btn btn-warning btn-icon waves-effect waves-light"><i class="ri-edit-2-line"></i></button>';
            $sub_array[] = '<button type="button" onClick="eliminar(' . $row["ROL_ID"] . ')" id="' . $row["ROL_ID"] . '" class="btn btn-danger btn-icon waves-effect waves-light"><i class="ri-delete-bin-5-line"></i></button>';
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
        $datos = $rol->obtener($_POST["rol_id"]);

        // Verificar si se obtuvo un resultado válido
        if ($datos !== false && is_array($datos)) {
            $output["ROL_ID"] = $datos["ROL_ID"];
            $output["ROL_NOM"] = $datos["ROL_NOM"];
            echo json_encode($output);
        } else {
            echo json_encode(["error" => "Rol no encontrado"]);
        }
        break;
    /*    TODO: ELIMINAR REGISTROS */
    case "eliminar":
        $rol->eliminar($_POST["rol_id"]);
        break;

    /*    TODO: COMBO PARA SELECCION EN FORMULARIO */
    case "combo":
        $rol_id = isset($_POST["rol_id"]) ? $_POST["rol_id"] : null;

        if ($rol_id !== null) {
            $datos = $rol->obtener($rol_id);
        } else {
            $datos = $rol->listar(); // O carga todos los roles si no te envían uno específico
        }

        if (is_array($datos) && count($datos) > 0) {
            $html = "";
            $html .= "<option selected>Seleccionar Rol</option>";
            foreach ($datos as $row) {
                $html .= "<option value='" . $row["ROL_ID"] . "'>" . $row["ROL_NOM"] . "</option>";
            }
            echo $html;
        }
        break;
}
