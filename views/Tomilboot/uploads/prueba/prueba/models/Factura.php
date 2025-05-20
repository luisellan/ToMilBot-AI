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


            $sqlFactura = "INSERT INTO TM_FACTURA (cli_id, total) VALUES (?, ?)";
            $queryFactura = $conectar->prepare($sqlFactura);
            $queryFactura->execute([
                $factura['cliente'],
                $totalFactura
            ]);


            $factura_id = $conectar->lastInsertId();


            $sqlDetalle = "INSERT INTO TM_FACTURA_DETALLE (factura_id, producto, precio, cantidad, subtotal, iva, total,monto_iva) 
               VALUES (:factura_id, :producto, :precio, :cantidad, :subtotal, :iva, :total, :monto_iva)";
            $queryDetalle = $conectar->prepare($sqlDetalle);

            foreach ($factura['productos'] as $producto) {
                $queryDetalle->execute([
                    ':factura_id' => $factura_id,
                    ':producto' => $producto['producto'],
                    ':precio' => $producto['precio'],
                    ':cantidad' => $producto['cantidad'],
                    ':subtotal' => $producto['subtotal'],
                    ':iva' => $producto['iva'],
                    ':total' => $producto['total'],
                    ':monto_iva' => $producto['monto_iva']
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



    public function get_Factura()
    {
        $conectar = parent::Conexion();
        $sql = "SP_L_TM_FACTURA_01";
        $query = $conectar->prepare($sql);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }


    public function get_Factura_id($id)
    {
        $conectar = parent::Conexion();
        $sql = "SP_L_TM_FACTURA_02 ?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $id);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }


}
