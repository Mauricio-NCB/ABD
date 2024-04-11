<?php

//Tabla Pelicula. Hecha para almacenar los datos de cada pelicula que se va a alquilar

class Pelicula {

    private $id;
    private $nombre;
    private $descripcion;
    private $precio;
    private $valoracion;
    private $comentarios;

    private function __construct ($id = null, $nombre, $descripcion, $precio, $valoracion, $comentarios) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->precio = $precio;
        $this->valoracion = $valoracion;
        $this->comentarios = $comentarios;
    }
}

?>