<?php
    if (!empty($_GET["id"])) {
        # code...
        $id = $_GET["id"];

        $sql = $conexion->query("DELETE FROM PERSONAS WHERE ID = $id");
        if ($sql == 1) {
            # code...
            echo '<div class="alert alert-warning" role="alert">
            Persona no eliminada correctamente.
          </div>';
        }else{
            echo '<div class="alert alert-danger" role="alert">
            Persona no eliminada correctamente.
          </div>';
        }
    }
?>