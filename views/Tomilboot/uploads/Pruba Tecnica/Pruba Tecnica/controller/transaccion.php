<?php

if (!empty($_POST["btntransaccion"])) {

    if (!empty($_POST["producto_id"]) && !empty($_POST["cantidad"]) && !empty($_POST["precio"])) {
        # code...
        $producto_id = $_POST["producto_id"];
        $cantidad_vendida = $_POST["cantidad"];
        $precio = $_POST["precio"];

        //Siempre al iniciar la transaccion
        $conexion->begin_transaction();
        try {
            // 1. Verificar si hay suficiente inventario
            $sql = $conexion->prepare("SELECT cantidad FROM productos WHERE id = ?");
            $sql->bind_param("i", $producto_id);
            $sql->execute();
            $resultado = $sql->get_result();
            $producto = $resultado->fetch_assoc();

            if ($producto['cantidad'] < $cantidad_vendida) {
                # code...
                echo "No se puede vender no hay productos en stock";
            }elseif ($producto === null) {
                throw new Exception("Producto no encontrado en el inventario.");
            }

            // 2. Actualizar el inventario
            $nueva_cantidad = $producto["cantidad"] - $cantidad_vendida;
            $sql_update = $conexion->prepare("UPDATE productos SET cantidad = ? WHERE id = ?");
            $sql_update->bind_param("ii", $nueva_cantidad, $producto_id);
            $sql_update->execute();

            // 3. Registrar la venta en la tabla ventas
            $sql_venta = $conexion->prepare("INSERT INTO ventas (producto_id, cantidad, precio)VALUES (?, ?, ?)");
            $sql_venta->bind_param("sss", $producto_id, $nueva_cantidad, $precio);
            $sql_venta->execute();

            // Si todo ha ido bien, confirmamos la transacción
            $conexion->commit();

            // Mensaje de éxito
            echo '<div class="alert alert-success" role="alert">
                Venta registrada correctamente y inventario actualizado.
              </div>';
            //code...
        } catch (Exception $e) {
            // Si ocurre un error, deshacemos todos los cambios
            $conexion->rollback();
    
            // Mostramos el error
            echo '<div class="alert alert-danger" role="alert">
                    Error: ' . $e->getMessage() . '
                  </div>';
        }
    }
}
