<?php
session_start();

use App\Db\Articulo;
use App\Db\Categoria;
use App\Utils\Utils;

require_once __DIR__ . "/../vendor/autoload.php";

$categorias = Categoria::read();

if (!isset($_GET['id'])) {
    header("Location:index.php");
    die();
}

$id = (int) $_GET['id'];
$articulo = Articulo::getArticuloById($id);

if (!$articulo) {
    header("Location:index.php");
    die();
}

if (isset($_POST['btn'])) {
    $nombre = ucfirst(htmlspecialchars(trim($_POST['nombre'])));
    $precio = (float) $_POST['precio'];
    $category_id = (int) $_POST['categoria_id'];
    $disponible = 2;
    $errores = false;

    if (isset($_POST['disponible'])) {
        $disponible = 1;
    }

    if (!Utils::isCadenaValida("nombre", $nombre, 3)) {
        $errores = true;
    } else {
        if (Utils::existeNombreUpdate($nombre, $id)) {
            $errores = true;
        }
    }

    if (!Utils::isPrecioValido("precio", $precio, 5)) {
        $errores = true;
    }

    if (!Utils::isCategoriaValida($category_id)) {
        $errores = true;
    }

    $imagen = $articulo->imagen;
    if (is_uploaded_file($_FILES['img_art']['tmp_name'])) {
        if (Utils::imgValida($_FILES['img_art']['type'], $_FILES['img_art']['size'])) {
            $imagen = "img/articulos/" . uniqid() . "-" .  $_FILES['img_art']['name'];
            if (!move_uploaded_file($_FILES['img_art']['tmp_name'], "./" . $imagen)) {
                $_SESSION['img_error'] = "No se ha podido subir la imagen";
                $errores = true;
            } else {
                if (basename($articulo->imagen != "default.png")) {
                    unlink("./" . $articulo->imagen);
                }
            }
        } else {
            $errores = true;
        }
    }

    if ($errores) {
        header("Location:update.php?id=$articulo->id");
        die();
    }

    (new Articulo)->setNombre($nombre)
        ->setDisponible($disponible == 1 ? "SI" : "NO")
        ->setPrecio($precio)
        ->setImagen($imagen)
        ->setCategoryId($category_id)
        ->update($id);

    header("Location:index.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Tailwind css -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Fontawesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Sweetalert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Modificar articulo</title>
</head>

<body>
    <form class="max-w-sm mx-auto" action="update.php?id=<?php echo $articulo->id ?>" method="POST" enctype="multipart/form-data">
        <div class="mb-5">
            <label for="nombre" class="block mb-2 text-sm font-medium text-gray-900 :text-white">Nombre</label>
            <input type="text" id="nombre" value="<?php echo $articulo->nombre ?>" name="nombre" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 :bg-gray-700 :border-gray-600 :placeholder-gray-400 :text-white :focus:ring-blue-500 :focus:border-blue-500" placeholder="name@flowbite.com" required>
            <?php Utils::mostrarErrores("nombre") ?>
        </div>
        <div class="mb-5">
            <label for="precio" class="block mb-2 text-sm font-medium text-gray-900 :text-white">Precio</label>
            <input type="text" id="precio" name="precio" value="<?php echo $articulo->precio ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 :bg-gray-700 :border-gray-600 :placeholder-gray-400 :text-white :focus:ring-blue-500 :focus:border-blue-500" placeholder="name@flowbite.com" required>
            <?php Utils::mostrarErrores("precio") ?>

        </div>
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="autor">
                Categoria
            </label>
            <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="categoria_id" name="categoria_id" type="text">
                <option>___Elige la categoria___</option>
                <?php
                foreach ($categorias as $categoria) {
                    echo $categoria->id == $articulo->category_id
                        ? "<option value='{$categoria->id}' selected>{$categoria->nombre}</option>"
                        : "<option value='{$categoria->id}'>{$categoria->nombre}</option>";
                }
                ?>
            </select>
            <?php Utils::mostrarErrores("categoria_error") ?>
        </div>
        <div class="mb-4 flex justify-between gap-10">
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2" for="autor">
                    Portada
                </label>
                <input type="file" name="img_art" oninput="img.src=window.URL.createObjectURL(this.files[0])" id="img_art" accept="image/*" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div>
                <img src="./<?php echo $articulo->imagen ?>" alt="default-book" width="150px" class="rounded" id="img">
            </div>
        </div>
        <?php Utils::mostrarErrores("img_error") ?>
        <div class="flex items-start my-5">
            <div class="flex items-center h-5">
                <input id="disponible" name="disponible" type="checkbox" <?php echo $articulo->disponible == "SI" ? "checked" : "" ?> class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-blue-300 :bg-gray-700 :border-gray-600 :focus:ring-blue-600 :ring-offset-gray-800 :focus:ring-offset-gray-800">
            </div>

            <label for="disponible" name="disponible" class="ms-2 text-sm font-medium text-gray-900 :text-gray-300">Disponible</label>
        </div>
        <button type="submit" name="btn" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center :bg-blue-600 :hover:bg-blue-700 :focus:ring-blue-800">Submit</button>
    </form>
</body>

</html>