<?php
class Vulnerabilidad extends Conectar {
    
    public function guardar($archivo, $tipo_vulnerabilidad, $codigo_vulnerable, $solucion_propuesta,$id_zip) {
        $conectar = parent::Conexion();
        parent::set_names();

        $sql = "INSERT INTO vulnerabilidades (archivo, tipo_vulnerabilidad, codigo_vulnerable, solucion_propuesta,id_zip) 
                VALUES (?, ?, ?, ?,?)";
        $stmt = $conectar->prepare($sql);
        $stmt->bindValue(1, $archivo);
        $stmt->bindValue(2, $tipo_vulnerabilidad);
        $stmt->bindValue(3, $codigo_vulnerable);
        $stmt->bindValue(4, $solucion_propuesta);
        $stmt->bindValue(5, $id_zip);
        $stmt->execute();
    }

    public function resertar($zip_name,$usu_id){
        $conectar = parent::Conexion();
        parent::set_names();
    
        $sql = "INSERT INTO zip_uploads (zip_name, upload_date,usu_id) 
                VALUES (?, NOW(),?)";
        $stmt = $conectar->prepare($sql);
        $stmt->bindValue(1, $zip_name);
        $stmt->bindValue(2, $usu_id);
        $stmt->execute();
    
        return $conectar->lastInsertId(); // <-- Muy importante
    }

}
?>
