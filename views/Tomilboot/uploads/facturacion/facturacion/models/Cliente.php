<?php
class Cliente extends Conectar
{
   /*  public function listarCliente()
    {
        $conectar = parent::Conexion();
        $sql = "SP_L_Cliente_01";
        $query = $conectar->prepare($sql);
       
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    } */

    public function listarCliente()
    {
        $conectar = parent::Conexion();
        $sql = "CALL SP_L_Cliente_01()"; // Corrección: Usar CALL
        $query = $conectar->prepare($sql);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }


    public function Cliente_id($id)
    {
        $conectar = parent::Conexion();
        $sql = "CALL SP_L_Cliente_02(?)";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $id);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    
}
