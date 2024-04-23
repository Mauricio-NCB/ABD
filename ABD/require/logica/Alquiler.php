<?php

//Para tabla Alquiler. Relacionado con cada alquiler hecho entre un usuario y una pelicula especifica.

class Alquiler {

    private $id;
    private $idUsuario;
    private $idPelicula;
    private $estado;
    private $fechaInicio;
    private $fechaFin;

    private function __construct($id, $idUsuario, $idPelicula, $fechaInicio, $fechaFin) {
        $this->id = $id;
        $this->idUsuario = $idUsuario;
        $this->idPelicula = $idPelicula;
        $this->fechaInicio = $fechaInicio;
        $this->fechaFin = $fechaFin;
    }

    
}

?>