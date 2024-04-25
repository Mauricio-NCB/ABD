<?php

//Para tabla Alquiler. Relacionado con cada alquiler hecho entre un usuario y una pelicula especifica.

class Alquiler {

    private $idUsuario;
    private $idPelicula;
    private $fechaInicio;
    private $fechaFin;

    private function __construct($idUsuario, $idPelicula, $fechaInicio, $fechaFin) {
        $this->idUsuario = $idUsuario;
        $this->idPelicula = $idPelicula;
        $this->fechaInicio = $fechaInicio;
        $this->fechaFin = $fechaFin;
    }
    
}

?>