<?php
class Empresas extends Conectar
{
   /*  public function listarEmpresas()
    {
        $conectar = parent::Conexion();
        $sql = "SP_L_Empresa_01";
        $query = $conectar->prepare($sql);
       
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    } */

    public function listarEmpresas()
    {
        $conectar = parent::Conexion();
        $sql = "CALL SP_L_EMPRESA_01()"; // Corrección: Usar CALL
        $query = $conectar->prepare($sql);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }


    public function Empresa_id($id)
    {
        $conectar = parent::Conexion();
        $sql = "CALL SP_L_EMPRESA_02(?)";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $id);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    
}
