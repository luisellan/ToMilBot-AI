<?php
require_once("../config/conexion.php");

class Factura extends Conectar
{
    public function guardarFactura($factura)
    {
        $conectar = parent::Conexion();

        try {
            
            $conectar->beginTransaction();

            
            $totalFactura = 0;
            foreach ($factura['productos'] as $prod) {
                $totalFactura += floatval($prod['total']);
            }

           
            $sqlFactura = "INSERT INTO facturas (emp_id, cli_id, total) VALUES (?, ?, ?)";
            $queryFactura = $conectar->prepare($sqlFactura);
            $queryFactura->execute([
                $factura['empresa'],
                $factura['cliente'],
                $totalFactura
            ]);

            
            $factura_id = $conectar->lastInsertId();

            
            $sqlDetalle = "INSERT INTO tm_factura_detalle (factura_id, producto, precio, cantidad, total) VALUES (?, ?, ?, ?, ?)";
            $queryDetalle = $conectar->prepare($sqlDetalle);

            
            foreach ($factura['productos'] as $producto) {
                $queryDetalle->execute([
                    $factura_id,
                    $producto['producto'],
                    $producto['precio'],
                    $producto['cantidad'],
                    $producto['total']
                ]);
            }

            
            $conectar->commit();
            return true;
        } catch (PDOException $e) {
            // En caso de error, revierte la transacción.
            $conectar->rollback();
            throw $e;
        }
    }
}
