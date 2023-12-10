<?php

use App\Db\Articulo;

require_once __DIR__ . "/../vendor/autoload.php";

if (!isset($_POST["id"])) {
    header("Location:./index.php");
    die();
}

$id = $_POST['id'];
$articulo = Articulo::getArticuloById($id);

if (!$articulo) {
    header("Location:./index.php");
    die();
}

Articulo::delete($id);

if (basename($articulo->imagen) != "default.png")
    unlink("./{$articulo->imagen}");

header("Location:./index.php");
die();
