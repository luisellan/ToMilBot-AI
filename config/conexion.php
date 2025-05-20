<?php
session_start();
class Conectar
{
    protected $dbh;

    protected function Conexion()
    {
        try {
            //code...
            $conectar = $this->dbh = new PDO("mysql:local=localhost;dbname=fersoft_tomilboot", "root", "");
            return $conectar;
        } catch (Exception $e) {
            //throw $th;
            print "!Error DB!: " . $e->getMessage() . '<br/>';
            die();
        }
    }

    public function set_names()
    {
        return $this->dbh->query("SET NAMES 'utf8'");
    }
    public static function ruta()
    {
        return "http://localhost/ToMilBot%20AI/";
    }
}
