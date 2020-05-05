<?php



class vacuna extends producto{


    public function __construct($marca, $precio, $stock, $foto)    
    {  
        parent::__construct($marca, $precio, $stock, $foto);

    }      


    public function Save($cadena){


        $gestor = fopen('vacuna.json', 'a+');
        echo $gestor;
    
        fwrite($gestor, $cadena . ",");
    
        fclose($gestor);
    
    }
    
    
    
        public function generarIdProducto(){
    
        $this->IDProducto = $this->marca . 'vacuna';
        return $this->IDProducto;   
        }
    }