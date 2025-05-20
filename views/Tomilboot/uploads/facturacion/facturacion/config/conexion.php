<?php
session_start();

class Conectar
{
    protected $dbh;

    protected function Conexion()
    {
        try {
            $conectar = $this->dbh = new PDO("mysql:local=localhost;dbname=Factura", "root", "");
            return $conectar;
        } catch (Exception $e) {
            print "Error DB" . $e->getMessage() . "<br/>";
        }
    }

    public function set_name()
    {
        if ($this->dbh) {
            return $this->dbh->query("SET NAMES 'utf8'");
        } else {
            throw new Exception('Conexión no inicializada.');
        }
    }

    public static  function ruta()
    {
        return "http://localhost/facturacion/";
    }
}
