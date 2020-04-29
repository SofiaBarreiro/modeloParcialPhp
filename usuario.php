<?php

class usuario{


    public $ID;
    public $nombre;
    public $dni;
    public $obraSocial;
    public $clave;
    public $tipo;



    public function __construct($nombre,$dni, $obraSocial, $clave, $tipo)    
    {  
        $this->nombre = $nombre;
        $this->dni = $dni;
        $this->obraSocial = $obraSocial;
        $this->clave = $clave;
        $this->tipo = $tipo;

    }   

    public function crearId ()    
    {  
        return $this->ID = ($this->nombre . $this->tipo);
    }   

}


