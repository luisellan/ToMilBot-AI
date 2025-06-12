<?php

session_start();
class Conectar
{
    protected $dbh;

    protected function Conexion(){
        try{
           
            $conectar = $this->dbh = new PDO("sqlsrv:Server=localhost\SQLEXPRESS;Database=Facturacion_electronica", "", "");
          
            return $conectar;
        }catch (Exception $e){
         
            print "Error Conexion BD". $e->getMessage() ."<br/>";
            die();
        }
    }


    public static  function ruta()
    {
        return "http://localhost/prueba/";
    }
}
