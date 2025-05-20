<?php
class Historial extends Conectar
{
    public function listar($usu_id)
    {
        $conectar = parent::Conexion();
        parent::set_names();

        $sql = "SELECT * FROM `zip_uploads` WHERE usu_id = ?";
        $stmt = $conectar->prepare($sql);
        $stmt->bindValue(1, $usu_id);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtener($id_zip, $usu_id)
    {
        $conectar = parent::Conexion();
        parent::set_names();

        $sql = "
        SELECT 
          z.zip_id,
          z.zip_name,
          z.upload_date,
          v.id             AS vul_id,
          v.archivo,
          v.tipo_vulnerabilidad,
          v.codigo_vulnerable,
          v.solucion_propuesta,
          v.fecha_subida
        FROM zip_uploads z
        LEFT JOIN vulnerabilidades v 
          ON z.zip_id = v.id_zip
        WHERE z.usu_id = ?    
          AND z.zip_id = ?    
        ORDER BY z.zip_id, v.id
    ";

        $stmt = $conectar->prepare($sql);
        $stmt->bindValue(1, $usu_id, PDO::PARAM_INT);
        $stmt->bindValue(2, $id_zip, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
