<?php
/* TODO: Llamando Clases */
require_once("../config/conexion.php");
require_once("../models/Usuario.php");
/* TODO: Inicializando clase */
$usuario = new Usuario();

switch ($_GET["op"]) {
    /* TODO: Guardar y editar, guardar cuando el ID este vacio, y Actualizar cuando se envie el ID */
    case "guardaryeditar":
        if (empty($_POST["usu_id"])) {
            $usuario->insertar(

                $_POST["usu_correo"],
                $_POST["usu_nom"],
                $_POST["usu_ape"],

                $_POST["usu_pass"],
                $_POST["rol_id"],

            );
        } else {
            $usuario->modificar(
                $_POST["usu_id"],

                $_POST["usu_correo"],
                $_POST["usu_nom"],
                $_POST["usu_ape"],
                $_POST["usu_img"]
                

            );
        }
        break;

    /* TODO: Listado de registros formato JSON para Datatable JS */
    case "listar":
        $datos = $usuario->listar();
        $data = array();
        foreach ($datos as $row) {
            $sub_array = array();
            $sub_array[] = $row["USU_ID"];

            $sub_array[] = $row["USU_CORREO"];
            $sub_array[] = $row["USU_NOM"];
            $sub_array[] = $row["USU_APE"];
            $sub_array[] = $row["USU_PASS"];
            $sub_array[] = $row["ROL_ID"];
            $sub_array[] = $row["FECH_CREA"];
            $sub_array[] = '<button type="button" onClick="editar(' . $row["USU_ID"] . ')" id="' . $row["USU_ID"] . '" class="btn btn-warning btn-icon waves-effect waves-light"><i class="ri-edit-2-line"></i></button>';
            $sub_array[] = '<button type="button" onClick="eliminar(' . $row["USU_ID"] . ')" id="' . $row["USU_ID"] . '" class="btn btn-danger btn-icon waves-effect waves-light"><i class="ri-delete-bin-5-line"></i></button>';
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

    /* TODO:Mostrar informacion de registro segun su ID */
    case "mostrar":
        $datos = $usuario->obtener($_POST["usu_id"]);

        if (is_array($datos) && count($datos) > 0) {
            $row = $datos; // no hacer foreach porque obtener() ya devuelve 1 solo registro
            $output["USU_ID"] = $row["USU_ID"];
            $output["USU_NOM"] = $row["USU_NOM"];
            $output["USU_APE"] = $row["USU_APE"];
            $output["USU_CORREO"] = $row["USU_CORREO"];
            $output["USU_PASS"] = $row["USU_PASS"];
            $output["ROL_ID"] = $row["ROL_ID"];
            echo json_encode($output);
        }
        break;


    /* TODO: Cambiar Estado a 0 del Registro */
    case "eliminar";
        $usuario->eliminar($_POST["usu_id"]);
        break;

    case "obtener":
        // Verifica si se está recibiendo el correo
        if (isset($_POST["usu_correo"])) {
            $datos = $usuario->VERIFICAR($_POST["usu_correo"]);
            echo json_encode($datos);
        }
        break;

    case "restaurar_password":
        // Validación básica
        if (isset($_POST["usu_correo"]) && isset($_POST["usu_pass"])) {
            $hash = password_hash($_POST["usu_pass"], PASSWORD_DEFAULT);
            $datos = $usuario->VERIFICAR($_POST["usu_correo"]);

            if (is_array($datos) && count($datos) > 0) {
                // Aquí debes pasar el ID del usuario, no el correo
                $usuario->restaurar_password($datos["USU_ID"], $hash);

                echo json_encode(["status" => "success", "message" => "Contraseña actualizada"]);
            } else {
                echo json_encode(["status" => "error", "message" => "Usuario no encontrado"]);
            }
        }
        break;
}
