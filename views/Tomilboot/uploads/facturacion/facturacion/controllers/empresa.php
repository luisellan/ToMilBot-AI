<?php
include_once("../config/conexion.php");
include_once("../models/Empresa.php");

$empresa = new Empresas();

switch ($_GET["op"]) {


    case "mostrar":
        $datos = $empresa->Empresa_id($_POST["emp_id"]);
        if (is_array($datos) == true and count($datos) > 0) {
            foreach ($datos as $row) {
                $output["EMP_ID"] = $row["EMP_ID"];
                $output["EMP_NOM"] = $row["EMP_NOM"];
                $output["EMP_TELEFONO"] = $row["EMP_TELEFONO"];
                $output["EMP_DIRECCION"] = $row["EMP_DIRECCION"];
                $output["EMP_CORREO"] = $row["EMP_CORREO"];
            }
            echo json_encode($output);
        }
        break;

    case "combo";
        $datos = $empresa->listarEmpresas();
        if (is_array($datos) == true and count($datos) > 0) {
            $html = "";
            $html .= "<option selected>Seleccionar</option>";
            foreach ($datos as $row) {
                $html .= "<option value='" . $row["EMP_ID"] . "'>" . $row["EMP_NOM"] . "</option>";
            }
            echo $html;
        }
        break;
}
