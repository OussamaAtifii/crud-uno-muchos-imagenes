<?php

namespace App\Db;

use PDO;
use PDOException;

class Categoria extends Conexion
{
    private int $id;
    private string $nombre;
    private string $descripcion;

    public function __construct()
    {
        parent::__construct();
    }

    // CRUD
    public function create()
    {
        $q = "insert into categorias(nombre, descripcion) values(:n, :d)";
        $stmt = parent::$conexion->prepare($q);

        try {
            $stmt->execute([
                ':n' => $this->nombre,
                ':d' => $this->descripcion
            ]);
        } catch (PDOException $ex) {
            die("Error al crear la categoria: " . $ex->getMessage());
        }

        parent::$conexion = null;
    }

    public static function read()
    {
        parent::setConexion();

        $q = "select * from categorias";
        $stmt = parent::$conexion->prepare($q);

        try {
            $stmt->execute();
        } catch (PDOException $ex) {
            die("Error al leer las categorias: " . $ex->getMessage());
        }

        parent::$conexion = null;
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // FAKER
    public static function generarCategorias(int $cantidad): void
    {
        if (self::hayCategorias()) return;

        $faker = \Faker\Factory::create();

        for ($i = 0; $i < $cantidad; $i++) {
            $nombre = $faker->unique()->word();
            $descripcion = $faker->text(random_int(20, 30));

            (new Categoria)->setNombre($nombre)
                ->setDescripcion($descripcion)
                ->create();
        }
    }

    // OTHERS
    private static function hayCategorias()
    {
        parent::setConexion();

        $q = "select id from categorias";
        $stmt = parent::$conexion->prepare($q);
        try {
            $stmt->execute();
        } catch (PDOException $ex) {
            die("Error en hayCategorias(): " . $ex->getMessage());
        }

        parent::$conexion = null;
        return $stmt->rowCount();
    }

    public static function getIds()
    {
        parent::setConexion();

        $q = "select id from categorias";
        $stmt = parent::$conexion->prepare($q);

        try {
            $stmt->execute();
        } catch (PDOException $ex) {
            die("Error en hayCategorias(): " . $ex->getMessage());
        }

        parent::$conexion = null;
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public static function getCategoriaById(int $id)
    {
        parent::setConexion();

        $q = "select * from categorias where id=:i";
        $stmt = parent::$conexion->prepare($q);

        try {
            $stmt->execute([
                ':i' => $id
            ]);
        } catch (PDOException $ex) {
            die("Error en hayCategorias(): " . $ex->getMessage());
        }

        parent::$conexion = null;
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    // SETTERS
    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;
        return $this;
    }

    public function setDescripcion(string $descripcion): self
    {
        $this->descripcion = $descripcion;
        return $this;
    }
}
