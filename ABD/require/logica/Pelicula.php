<?php
namespace abd;
use abd\Aplicacion as Aplicacion;

//Tabla Pelicula. Hecha para almacenar los datos de cada pelicula que se va a alquilar

class Pelicula {

    //Atributos valoracion y comentarios provenientes de la tabla Valoraciones

    private $id;
    private $nombre;
    private $descripcion;
    private $precio;
    private $valoracion;
    private $comentarios;

    private function __construct ($id, $nombre, $descripcion, $precio, $valoracion = null, $comentarios = []) {
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

        $pelicula = new Pelicula(null, $nombre, $descripcion, $precio);
        if (!self::anadir($pelicula)) {
            error_log("Error de insercion: No se ha podido insertar de manera correcta la nueva pelicula");
            $result = false;
        }

        return $result;
    }

    private static function anadir($pelicula) {
        $result = false;
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("INSERT INTO pelicula(nombre, descripcion, precio)
                        VALUES ('%s', '%s', '%s')"
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

    public static function mismaPelicula($nombrePelicula){
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("SELECT * FROM pelicula P WHERE P.nombre='%s'", $conn->real_escape_string($nombrePelicula));
        $rs = $conn->query($query);
        $result = false;
        if ($rs) {
            $fila = $rs->fetch_assoc();
            if ($fila) {
                $result = new Ropa($fila['Nombre'], $fila['Stock'], 
                $fila['Color'], $fila['Categoria'], $fila['Talla'], 
                $fila['Descripcion'], $fila['Precio'], $fila ['Imagen']);
            }
            $rs->free();
        } else {
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

    static function mostrarCatalogo() {
        
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("SELECT * FROM pelicula");
        $result = $conn->query($query);
        
        $catalogo = [];
        $i = 0;

        if($result) {

            foreach($result as $fila) {

                $valoracion = obtenerValoracion($fila['id']);
                $comentarios = obtenerComentarios($fila['id']);
                $catalogo[$i] = new Pelicula($fila['id'], $fila['nombre'], $fila['descripcion'], $fila['precio'], $fila['valoracion'], $fila['comentario']);
            }

            $result->free();         
        }

        return $catalogo;

    }

    static function obtenerValoracion($idPelicula) {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = "SELECT AVG(puntuacion) FROM valoracion V WHERE V.idPelicula= '$idPelicula'";
        $result = $conn->query($query);
        
        if ($result) {
            return $result;
        }
        
        return null;
    }

    static function obtenerComentarios($idPelicula) {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = "SELECT comentario FROM valoracion V WHERE V.idPelicula= '$idPelicula'";
        $result = $conn->query($query);
        $comentarios = [];
        $i = 0;

        if ($result) {
            foreach($result as $fila) {
                $comentarios[$i] = $fila;
                $i++;
            }
            $result->free();

            return $comentarios;
        }
        else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");

            return null;
        }
    }

}

?>