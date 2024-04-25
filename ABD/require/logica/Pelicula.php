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

    private function __construct ($id = null, $nombre, $descripcion, $precio, $valoracion = null, $comentarios = []) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->precio = $precio;
        $this->valoracion = $valoracion;
        $this->comentarios = $comentarios;
    }

    public static function anadirPelicula($nombre, $descripcion, $precio) {
        
        $result = true;

        // Se almacena la nueva pelicula creada

        $pelicula = new Pelicula($nombre, $descripcion, $precio);
        if (!self::anadir($pelicula)) {
            error_log("Error de insercion: No se ha podido insertar de manera correcta la nueva pelicula");
            $result = false;
        }

        return $result;
    }

    private static function anadir($pelicula) {
        $result = false;
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("INSERT INTO pelicula(id, nombre, descripcion, precio)
                        VALUES ('%s', '%s', '%s', '%s')"
                    , $conn->real_escape_string($pelicula->nombre)
                    , $conn->real_escape_string($pelicula->descripcion)
                    , $conn->real_escape_string($pelicula->precio));
        
        if ($conn->query($query)) {
            $pelicula->id = $conn->insert_id;
            $result = true;
        }
        else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        return $result;
    }

    public static function eliminarPelicula($nombre) {
        return $result = self::eliminar($nombre);
    }

    private static function eliminar($nombre) {
        $result = false;
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("DELETE * FROM pelicula P WHERE P.nombre = $nombre");
        if ($conn->query($query)) {
            $result = true;
        }
        else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        return $result;
    }

    static function mostrarPelicula($id) {
        $pelicula = self::buscarPelicula($id);
        return $pelicula;
    }

    static function mostrarCartelera() {
        $pelicula = self::filtrarPelicula();
        return $pelicula;
    }

}

?>