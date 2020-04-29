<?php


require __DIR__ . '/usuario.php';
require __DIR__ . '/producto.php';
require __DIR__ . '/medicamento.php';
require __DIR__ . '/vacuna.php';

$request_Method = $_SERVER["REQUEST_METHOD"];
$path_info = $_SERVER["PATH_INFO"];


switch ($path_info) {

    case '/usuario':

        if ($request_Method == 'POST') {
  
         $nombre = $_POST['nombre'] ?? null;
         $obraSocial = $_POST['obra_social'] ?? null;
         $tipo = $_POST['tipo'] ?? null;
         $clave = $_POST['clave'] ?? null;
         $dni = $_POST['dni'] ?? null;

        if($nombre != null && $obraSocial != null && $tipo != null && $clave != null && $dni != null){
        
            $usuario = new usuario($nombre,$dni, $obraSocial, $clave, $tipo);   
            
            $usuario->ID = $usuario->crearId(); 
        
            var_dump($usuario);
        } 

    }else {
            
        echo 'Method not allowed';

    }
        break;
    case '/login':

    if ($request_Method == 'POST') {
        /* (POST) login: Recibe nombre y clave y si son correctos devuelve un JWT, de lo contrario informar lo
            sucedido.        */  
            $nombre = $_POST['nombre'] ?? null;
            $clave = $_POST['clave'] ?? null;
            if($nombre != null && $clave != null){
                if(true){

                    //verificar que este guardado

                }else{
                    echo 'nombre de usuario o clave incorrecta';

                }

            }else{


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

        } else {
        if ($request_Method == 'POST'){

            if($usuario->tipo == 'admin') {
    /* (POST) stock: (Solo para admin). Recibe producto (vacuna o medicamento), marca, precio, stock y foto y
    lo guarda en un archivo en formato JSON, a la imagen la guarda en la carpeta imágenes. Generar un
    identificador (id) único para cada producto */
            
            $marca = $_POST['marca'] ?? null;
            $precio = $_POST['precio'] ?? null;
            $stock = $_POST['stock'] ?? null;
            $foto = $_POST['foto'] ?? null;
            $producto = $_POST['producto'] ?? null;


            if($marca != null && $precio != null && $stock != null&& $foto != null && $producto != null){
            
                if($producto == 'vacuna'){


                    $vacuna = new vacuna($marca, $precio, $stock, $foto);
                    $vacuna->IDProdcto = generarIdProducto();
                }else{

                    if($producto == 'medicamento'){

                        $medicamento = new medicamento($marca, $precio, $stock, $foto);
                        $medicamento->IDProdcto = generarIdProducto();
                    }
                }

                
                //guardar todo armar objeto

            }

        }else{

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
            } else {
                if ($request_Method == 'GET') {
/* 
ventas: Si es admin muestra listado con todas las ventas, si es usuario solo las ventas de dicho
usuario */               
                }else{
    
                    echo 'Method not allowed';
    
                }
            }
    
            break;
}

