<?php



abstract class producto{


    public $IDProdcto;
    public $marca;
    public $precio;
    public $stock;
    public $foto;

    public function __construct($IDProdcto, $marca, $precio, $stock, $foto)    
    {  
        $this->IDProdcto = $IDProdcto;
        $this->marca = $marca;
        $this->precio = $precio;
        $this->stock = $stock;
        $this->foto = $foto;
    }   



}


