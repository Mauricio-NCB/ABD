<?php

use Aplicacion;

//Tabla Pelicula. Hecha para almacenar los datos de cada pelicula que se va a alquilar

class Pelicula {

    //Atributos valoracion y comentarios provenientes de la tabla Valoraciones

    private $id;
    private $nombre;
    private $descripcion;
    private $precio;
    private $valoracion;
    private $comentarios;

    private function __construct ($id = null, $nombre, $descripcion, $precio, $valoracion, $comentarios = []) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->precio = $precio;
        $this->valoracion = $valoracion;
        $this->comentarios = $comentarios;
    }

    public static function anadirPelicula($nombre, $descripcion, $precio, $valoracion) {
        
        $result = true;

        // Se almacena la nueva pelicula creada

        $pelicula = new Pelicula($nombre, $descripcion, $precio, $valoracion);
        if (!$pelicula->anadir($pelicula)) {
            error_log("Error de insercion: No se ha podido insertar de manera correcta la nueva pelicula");
            $result = false;
        }

        return $result;
    }

    private static function anadir($pelicula) {
        $result = false;
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf ("INSERT INTO Peliculas(Nombre, Descripcion, Precio, Valoracion)");
    }
}

?>