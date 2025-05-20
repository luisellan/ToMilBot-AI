<?php
class Producto extends Conectar
{
    public function get_productos()
    {
        $conectar = parent::Conexion();
        $sql = "SP_L_PRODUCTO_01";
        $query = $conectar->prepare($sql);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }


    public function get_producto_id($prod_id)
    {
        $conectar = parent::Conexion();
        $sql = "SP_L_PRODUCTO_02 ?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $prod_id);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    
}
