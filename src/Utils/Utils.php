<?php

namespace App\Utils;

use App\Db\Articulo;
use App\Db\Categoria;

class Utils
{
    public static array $tiposImagen = [
        "image/gif",
        "image/png",
        "image/jpg",
        "image/jpeg",
        "image/bmp",
        "image/webp",
        "image/svg+xml",
        "image/x-icon"
    ];

    public static function isCadenaValida($nombre, $valor, $longitud): bool
    {
        if (strlen($valor) < $longitud) {
            $_SESSION[$nombre] = "El campo $nombre no puede tener menos de $longitud caracteres";
            return false;
        }

        return true;
    }

    public static function isPrecioValido($nombre, $valor, $longitud): bool
    {
        if ($valor < $longitud) {
            $_SESSION[$nombre] = "El campo $nombre tiene que ser como minimo $longitud";
            return false;
        }

        return true;
    }

    public static function isCategoriaValida(int $id): bool
    {
        if (Categoria::getCategoriaById($id)) return true;

        $_SESSION['categoria_error'] = "La categoria no existe o no es válida";
        return false;
    }

    public static function existeNombre(string $nombre): bool
    {
        if (Articulo::getArticuloByName($nombre)) {
            $_SESSION['nombre'] = "Este nombre ya está en uso, introduzca otro";
            return true;
        }

        return false;
    }

    public static function existeNombreUpdate(string $titulo, int $id): bool
    {
        if (Articulo::getArticuloByName($titulo, $id)) {
            $_SESSION['nombre'] = "Este nombre ya está en uso, introduzca otro";
            return true;
        }

        return false;
    }

    public static function imgValida(string $type, int $size)
    {
        if (!in_array($type, self::$tiposImagen)) {
            $_SESSION['img_error'] = "El tipo de imagen es inválido";
            return false;
        }

        if ($size > 2000000) {
            $_SESSION['img_error'] = "El tamaño de imagen es inválido";
            return false;
        }

        return true;
    }

    public static function mostrarErrores(string $nombreError): void
    {
        if (isset($_SESSION[$nombreError])) {
            echo "<p class='italic text-sm text-red-600 mt-2'>{$_SESSION[$nombreError]}</p>";
            unset($_SESSION[$nombreError]);
        }
    }
}
