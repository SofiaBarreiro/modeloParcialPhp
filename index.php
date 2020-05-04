<?php


require __DIR__ . '/usuario.php';
require __DIR__ . '/producto.php';
require __DIR__ . '/medicamento.php';
require __DIR__ . '/vacuna.php';

$request_Method = $_SERVER["REQUEST_METHOD"];
$path_info = $_SERVER["PATH_INFO"];

/* 
session_start();
$session = $_SESSION ?? false;

if ($session == false) {

    $session = $_SESSION["CLAVE"] = "VALOR";


    $session = $_SESSION['nombre'] = $_GET['nombre'];
}



die(); */


session_start();
$session = $_SESSION ?? false;


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
                    $_SESSION['clave'] =  $usuario->clave;
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

                    //devuelvo un JWT

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



            $file = fopen("..\ModeloParcial\productos.json", "r");

            while (!feof($file)) {
                $texto = fgets($file);
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
                    $foto = $_FILES['name'] ?? null;
                    $producto = $_POST['producto'] ?? null;


                    if ($marca != null && $precio != null && $stock != null && $foto != null && $producto != null) {

                        $file = fopen("..\ModeloParcial\productos.json", "+a");


                        $foto = $_FILES['foto'] ??  null;


                        if ($producto == 'vacuna') {


                            $vacuna = new vacuna($marca, $precio, $stock, $_FILES['name']);


                            $vacuna->IDProdcto = $vacuna->generarIdProducto(); 

                            fwrite($file, $vacuna);
                            producto::GuardarFoto($foto);
                        } else {

                            if ($producto == 'medicamento') {

                                $medicamento = new medicamento($marca, $precio, $stock,  $_FILES['name']);
                                $medicamento->IDProdcto = $medicamento->generarIdProducto(); 
                                fwrite($file, $medicamento);
                                producto::GuardarFoto($foto);
                            }
                        }

                        //guardar imagenes en la carpeta
                        fclose($file);
                    }
                } else {

                    echo 'Method not allowed';
                }
            }
        }

        break;
    case '/ventas':
        if ($request_Method == 'POST') {
            /*   Recibe id y cantidad de producto y usuario y si existe esa cantidad de
            producto devuelve el monto total de la operación. Si se realiza la venta restar el stock al producto y
            guardar la venta serializado en el archivo ventas.xxx. */

            $file = fopen("productos.json", "+r");

            while (!feof($file)) {
                $texto = fgets($file);
            }

            //leo todo

            //comparo

            //resto el stock



            fclose($file);

            //guardo el producto


            $venta  = $objeto ?? null;

            if ($venta != null) {

                $file2 = fopen("..\ModeloParcial\\ventas.json", "+a");

                fwrite($file, serialize($venta));

                fclose($file2);
            }
        } else {
            if ($request_Method == 'GET') {

                /* 
                ventas: Si es admin muestra listado con todas las ventas, si es usuario solo las ventas de dicho
                usuario */

                $tipo = $_SESSION['tipo'] ?? null;

                if ($tipo == 'admin') {



                    //es admin


                    $file = fopen("..\ModeloParcial\\ventas.json", "r");

                    while (!feof($file)) {
                        $texto = fread($file, sizeof("..\ModeloParcial\\ventas.json"));
                        $objeto = unserialize($texto);

                        echo $texto;


                    }

                } else {

                    if ($tipo == 'usuario') {



                    while (!feof($file)) {
                        $texto = fread($file, sizeof("..\ModeloParcial\\ventas.json"));
                        $objeto = unserialize($texto);

                        //comparar si es el mismo usuario;
                        

                    }

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
