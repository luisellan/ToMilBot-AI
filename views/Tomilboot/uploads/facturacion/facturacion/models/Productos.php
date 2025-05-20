<?php
class Productos extends Conectar
{
   /*  public function listarProductos()
    {
        $conectar = parent::Conexion();
        $sql = "SP_L_PRODUCTO_01";
        $query = $conectar->prepare($sql);
       
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    } */

    public function listarProductos()
    {
        $conectar = parent::Conexion();
        $sql = "CALL SP_L_PRODUCTO_01()"; // Corrección: Usar CALL
        $query = $conectar->prepare($sql);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }


    public function producto_id($id)
    {
        $conectar = parent::Conexion();
        $sql = "CALL SP_L_PRODUCTO_02(?)";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $id);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    
}
