<?php

require __DIR__ . '/usuario.php';
require __DIR__ . '/producto.php';
require __DIR__ . '/medicamento.php';
require __DIR__ . '/vacuna.php';

require_once 'vendor/autoload.php';

use Firebase\JWT\JWT;


$request_Method = $_SERVER["REQUEST_METHOD"];
$path_info = $_SERVER["PATH_INFO"];

/*
session_start();
$session = $_SESSION ?? false;

if ($session == false) {

$session = $_SESSION["CLAVE"] = "VALOR";

$session = $_SESSION['nombre'] = $_GET['nombre'];
}


session_destroy();//para borrar las cookies
setcookie('nombre', 'juan');

die(); */



session_start();
$session = $_SESSION ?? false;



/*
 NOTE: This will now be an object instead of an associative array. To get
 an associative array, you will need to cast it as such:
*/




switch ($path_info) {

    case '/usuario':

        if ($request_Method == 'POST') {

            $nombre = $_POST['nombre'] ?? null;
            $obraSocial = $_POST['obra_social'] ?? null;
            $tipo = $_POST['tipo'] ?? null;
            $clave = $_POST['clave'] ?? null;
            $dni = $_POST['dni'] ?? null;

            if ($nombre != null && $obraSocial != null && $tipo != null && $clave != null && $dni != null) {

                $usuario = new usuario($nombre, $dni, $obraSocial, $clave, $tipo);

                $usuario->ID = $usuario->crearId();

                //var_dump($usuario);

                if ($session == false) {

                    $_SESSION['nombre'] = $usuario->nombre;
                    $_SESSION['clave'] = $usuario->clave;
                    $_SESSION['tipo'] = $usuario->tipo;
                    var_dump($_SESSION);
                } else {

                    var_dump($_SESSION);
                }
            }
        } else {

            echo 'Method not allowed';
        }
        break;
    case '/login':

        if ($request_Method == 'POST') {
            /* (POST) login: Recibe nombre y clave y si son correctos devuelve un JWT, de lo contrario informar lo
            sucedido.        */

            $nombre = $_POST['nombre'] ?? null;
            $clave = $_POST['clave'] ?? null;

            if ($nombre != null && $clave != null) {

                if ($_SESSION['nombre'] == $nombre && $_SESSION['clave'] == $clave) {

                    $key = "example_key";
                    $payload = usuario::crearToken($clave, $nombre);
                    $jwt = JWT::encode($payload, $key);

                    $decoded = JWT::decode($jwt, $key, array('HS256'));
                    $decoded_array = (array) $decoded;
                    print_r($jwt);
                    print_r($jwt);
                    $_SESSION['token'] = $jwt;

                    $header = getallheaders();


                    var_dump($header);
                } else {
                    echo 'nombre de usuario o clave incorrecta';
                }
            } else {

                echo 'Por favor complete los campos que faltan';
            }
        } else {

            echo 'Method not allowed';
        }
        break;
    case '/stock':

        if ($request_Method == 'GET') {

            /* (GET) stock: Muestra la lista de productos.
             */

            $file = fopen("productos.json", "r");

            while (!feof($file)) {
                $texto = fgets($file);
                echo $texto;
            }
            fclose($file);
        } else {

            if ($request_Method == 'POST') {

                $tipo = $_SESSION['tipo'] ?? null;

                if ($tipo == 'admin') {
                    /* (POST) stock: (Solo para admin). Recibe producto (vacuna o medicamento), marca, precio, stock y foto y
                    lo guarda en un archivo en formato JSON, a la imagen la guarda en la carpeta imágenes. Generar un
                    identificador (id) único para cada producto */

                    $marca = $_POST['marca'] ?? null;
                    $precio = $_POST['precio'] ?? null;
                    $stock = $_POST['stock'] ?? null;
                    $foto = $_FILES['foto'] ?? null;
                    $producto = $_POST['producto'] ?? null;

                    if ($marca != null && $precio != null && $stock != null && $foto != null && $producto != null) {

                        $file = fopen("productos.json", "a+");

                        if ($producto == 'vacuna') {

                            $vacuna = new vacuna($marca, $precio, $stock, $foto['name']);

                            $vacuna->IDProducto = $vacuna->generarIdProducto();

                            $json = json_encode($vacuna);
                            fwrite($file, $json);

                            /* var_dump($vacuna); */
                            producto::GuardarFoto($foto);

                            list($txt, $ext) = explode(".", $_FILES['foto']['name']);

                            $watermark = "Sofia Barreiro"; // Add your own water mark here
                            addTextWatermark($_FILES['foto']['name'], $watermark, $ext, $_FILES['foto']['name']);
                        } else {

                            if ($producto == 'medicamento') {

                                $medicamento = new medicamento($marca, $precio, $stock, $foto['name']);
                                $medicamento->IDProdcto = $medicamento->generarIdProducto();
                                $json = json_encode($medicamento);

                                fwrite($file, $json);
                                producto::GuardarFoto($foto);
                            }
                        }

                        fclose($file);
                    }
                } else {

                    echo 'No posee permisos para esta operacion';
                }
            }
        }

        break;
    case '/ventas':
        if ($request_Method == 'POST') {
            /*   Recibe id y cantidad de producto y usuario y si existe esa cantidad de
            producto devuelve el monto total de la operación. Si se realiza la venta restar el stock al producto y
            guardar la venta serializado en el archivo ventas.xxx. */

            $file2 = fopen("ventas.json", "a+");


            $id_producto = $_POST['id_producto'] ?? null;
            $cantidad = $_POST['cantidad'] ?? null;
            $usuario = $_POST['usuario'] ?? null;

            if ($id_producto != null && $cantidad != null && $usuario != null) {

                $file = fopen("productos.json", "r");


                while (!feof($file)) {
                    $texto = fgets($file);
                    //echo $texto;

                    fwrite($file2, json_encode(serialize($texto)));
                }



                //leo todo

                //comparo

                //resto el stock

                fclose($file);

                //guardo el producto



            }
            $venta  = $objeto ?? null;

            if ($venta != null) {

                echo serialize($venta);


                fwrite($file, serialize($venta));

                fclose($file2);
            } else {

                echo 'falta completar los campos';
            }
        } else {
            if ($request_Method == 'GET') {

                /*
                ventas: Si es admin muestra listado con todas las ventas, si es usuario solo las ventas de dicho
                usuario */

                $tipo = $_SESSION['tipo'] ?? null;

                if ($tipo == 'admin') {

                    //es admin


                    $file2 = fopen("ventas.json", "r");

                    while (!feof($file2)) {
                        $texto = fread($file2, sizeof("ventas.json"));
                        $objeto = unserialize($texto);

                        echo $texto;
                    }

                    fclose($file2);
                } else {

                    $file2 = fopen("ventas.json", "r");


                    if ($tipo == 'usuario') {

                        while (!feof($file2)) {
                            $texto = fread($file2, sizeof("ventas.json"));
                            $objeto = unserialize($texto);

                            //comparar si es el mismo usuario;

                        }
                        fclose($file2);

                        //es usuario
                    } else {
                        //no es usuario ni admin
                        echo 'error en autentificacion';
                    }
                }
            } else {

                //no es ni post ni get
                echo 'Method not allowed';
            }
        }

        break;
    default:
        echo 'direccion no existente';
        break;
}


function addTextWatermark($src, $watermark,  $ext ,$save = NULL)
{



    list($width, $height) = getimagesize($src);
    $image_color = imagecreatetruecolor($width, $height);

    switch ($ext) {

        case 'jpg':
            $image = imagecreatefromjpeg($src);
            break;
        case 'png':
            $image = imagecreatefrompng($src);
            break;
        case 'gif':
            $image = imagecreatefromgif($src);
            break;
        default: echo 'error tipo de archivo invalido';
        break;
    }
   

  


    imagecopyresampled($image_color, $image, 0, 0, 0, 0, $width, $height, $width, $height);
    $txtcolor = imagecolorallocate($image, 255, 255, 255);
    $fuente = "AlexBrush-Regular.ttf";
    $font_size = 50;
    imagettftext($image_color, $font_size, 0, 50, 150, $txtcolor, $fuente, $watermark);
    if ($save <> '') {
        imagejpeg($image_color, $save, 100);
    } else {
        header('Content-Type: image/jpeg');
        imagejpeg($image_color, null, 100);
    }



    imagedestroy($image);
    imagedestroy($image_color);
}
