<?php
class Rol extends Conectar
{
    public function listar()
    {
        $conectar = parent::Conexion();
        parent::set_names();

        $sql = "SELECT * FROM TM_ROL WHERE EST = 1";
        $stmt = $conectar->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function obtener($rol_id)
    {
        $conectar = parent::Conexion();
        parent::set_names();

        $sql = "SELECT * FROM TM_ROL WHERE EST = 1 and ROL_ID = ?";
        $stmt = $conectar->prepare($sql);
        $stmt->bindValue(1, $rol_id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function eliminar($rol_id)
    {
        $conectar = parent::Conexion();
        parent::set_names();

        $sql = "UPDATE TM_ROL SET EST = 0 WHERE rol_ID id = ?";
        $stmt = $conectar->prepare($sql);
        $stmt->bindValue(1, $rol_id);
        $stmt->execute();
    }

    public function insertar($rol_nom)
    {
        $conectar = parent::Conexion();
        parent::set_names();

        $sql = "INSERT INTO TM_ROL
    (ROL_NOM)
    VALUES (?, NOW(), 1)";

        $stmt = $conectar->prepare($sql);
        $stmt->bindValue(1, $rol_nom);
    
        $stmt->execute();
    }


    public function modificar($rol_id, $rol_nom)
    {
        $conectar = parent::Conexion();
        parent::set_names();

        $sql = "UPDATE TM_ROL SET 
            ROL_NOM = ?, 

            WHERE ROL_ID = ?";

        $stmt = $conectar->prepare($sql);
        $stmt->bindValue(1, $rol_nom);
        $stmt->bindValue(2, $rol_id);
       
        $stmt->execute();
    }

}