<?php
session_start();
require_once("env.php");          
loadEnv(__DIR__ . '/../.env');    

class Conectar
{
    protected $dbh;

    protected function Conexion()
    {
        try {
            $host = $_ENV['DB_HOST'] ?? 'localhost';
            $dbname = $_ENV['DB_NAME'] ?? 'defaultdb';
            $user = $_ENV['DB_USER'] ?? 'root';
            $pass = $_ENV['DB_PASS'] ?? '';
            $charset = $_ENV['DB_CHARSET'] ?? 'utf8';

            $dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";
            $this->dbh = new PDO($dsn, $user, $pass);
            return $this->dbh;
        } catch (Exception $e) {
            print "!Error DB!: " . $e->getMessage() . '<br/>';
            die();
        }
    }

    public function set_names()
    {
        return $this->dbh->query("SET NAMES '" . ($_ENV['DB_CHARSET'] ?? 'utf8') . "'");
    }

    public static function ruta()
    {
        return "http://localhost/ToMilBot%20AI/";
    }
}
