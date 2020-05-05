<?php



abstract class producto
{


    public $IDProdcto;
    public $marca;
    public $precio;
    public $stock;
    public $foto;

    public function __construct( $marca, $precio, $stock, $foto)
    {
        $this->marca = $marca;
        $this->precio = $precio;
        $this->stock = $stock;
        $this->foto = $foto;
    }



    public static function GuardarFoto($file)
    {
        $file1 = $file['name'];
        $temp_name = $file['tmp_name'];
        $destination = $file1;
        move_uploaded_file($temp_name, $destination);

        return $destination;
    }


    public static function moverFoto($file)
    {
        $file1 = $file['name'];
        $temp_name = $file['tmp_name'];
        $destination = '..\ModeloParcial\backUpFotos' . '\\' . $file1;

        move_uploaded_file($temp_name, $destination);

        return $destination;
    }

    public static function cambiarNombre($file)
    {

        $file1 = $file['name'];
        $nuevoNombre = rename($file1, '..\\ModeloParcial\\backUpFotos' . '\\' . "nuevaFoto.jpg");
        return $nuevoNombre;
    }

    
}