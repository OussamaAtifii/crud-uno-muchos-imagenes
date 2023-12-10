<?php

use App\Db\Articulo;
use App\Db\Categoria;

require_once __DIR__ . "/../vendor/autoload.php";

Categoria::generarCategorias(10);
Articulo::generarArticulos(20);

$articulos = Articulo::read();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Tailwind css -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Fontawesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Sweetalert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Lista de articulos | Categorias</title>
</head>

<body>
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg w-1/2 mx-auto my-20">
        <div class="flex justify-end mb-2">
            <a href="./crear.php" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                <i class="fa-regular fa-square-plus mr-2"></i>Añadir articulo
            </a>
        </div>
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        Nombre
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Categoria
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Disponible
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Action
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($articulos as $articulo) {
                    $disponible = $articulo->disponible == "SI"
                        ? '<div class="h-2.5 w-2.5 rounded-full bg-green-500 me-2"></div> En stock'
                        : '<div class="h-2.5 w-2.5 rounded-full bg-red-500 me-2"></div> Fuera de stock';

                    echo <<<TXT
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <th scope="row" class="flex items-center px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white">
                            <img class="w-10 h-10 rounded-full" src="./{$articulo->imagen}" alt="articulo {$articulo->nombre} image">
                            <div class="ps-3">
                                <div class="text-base font-semibold">{$articulo->nombre}</div>
                            </div>
                        </th>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                {$articulo->nomCat}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                {$disponible}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <form action="./borrar.php" method="POST">
                                <a href="update.php?id=$articulo->id" class="font-medium text-blue-600 dark:text-blue-500 hover:underline"><i class="fa-solid fa-file-pen mx-2 text-base"></i></a>
                                <input type="text" name="id" hidden value="{$articulo->id}">
                                <button type="submit" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">
                                    <i class="fa-solid fa-trash-arrow-up text-base text-red-500"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                TXT;
                }
                ?>
            </tbody>

        </table>
    </div>


</body>

</html>