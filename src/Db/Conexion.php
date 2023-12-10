<?php

namespace App\Db;

use \PDO;
use \PDOException;

class Conexion
{
    protected static $conexion;

    public function __construct()
    {
        self::setConexion();
    }

    public static function setConexion()
    {
        if (self::$conexion != null) return;

        $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->load();

        $user = $_ENV['USER'];
        $password = $_ENV['PASSWORD'];
        $host = $_ENV['HOST'];
        $db = $_ENV['DB'];

        $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
        $options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING];

        try {
            self::$conexion = new PDO($dsn, $user, $password, $options);
        } catch (PDOException $ex) {
            die("Error al conectar a la base de datos: " . $ex->getMessage());
        }
    }
}
