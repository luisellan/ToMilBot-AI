<?php
class Cliente extends Conectar
{
    public function get_clientes()
    {
        $conectar = parent::Conexion();
        $sql = "SP_L_CLIENTE_01";
        $query = $conectar->prepare($sql);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }


    public function get_cliente_id($cli_id)
    {
        $conectar = parent::Conexion();
        $sql = "SP_L_CLIENTE_02 ?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $cli_id);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    
}
