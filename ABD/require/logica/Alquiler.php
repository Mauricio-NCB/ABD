<?php
namespace abd;

//Para tabla Alquiler. Relacionado con cada alquiler hecho entre un usuario y una pelicula especifica.

class Alquiler {

    private $id;
    private $idUsuario;
    private $idPelicula;
    private $fechaInicio;
    private $fechaFin;

    // A efectos practicos fechaFin aun no se usa. Opcionalmente podemos incluirlo

    private function __construct($id, $idUsuario, $idPelicula, $fechaInicio, $fechaFin) {
        $this->id = $id;
        $this->idUsuario = $idUsuario;
        $this->idPelicula = $idPelicula;
        $this->fechaInicio = $fechaInicio;
        $this->fechaFin = $fechaFin;
    }
    
    public function getId() {
        return $id;
    }

    public function getIdUsuario() {
        return $idUsuario;
    }

    public function getIdPelicula() {
        return $idPelicula;
    }

    public function getFechaInicio() {
        return $fechaInicio;
    }

    public function getFechaFin() {
        return $fechaFin;
    }

    public static function nuevoAlquiler($idUsuario, $idPelicula, $fechaInicio, $fechaFin) {

        $alquiler = new Alquiler(null, $idUsuario, $idPelicula, $fechaInicio, $fechaFin);
        $result = true;

        if (!$alquiler->anadir()){
            error_log("Error de inserción: No se ha podido insertar de manera correcta");
            $result  = false;
        }
        return $result;
    }

    private function anadir() {
        $result = false;
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("INSERT INTO alquiler(idUsuario, idPelicula, fechaInicio, fechaFin) 
                                VALUES ('%s', '%s', '%s', '%s')", 
                            $conn->real_escape_string($this->idUsuario), 
                            $conn->real_escape_string($this->idPelicula),
                            $conn->real_escape_string($this->fechaInicio),
                            $conn->real_escape_string($this->fechaFin));

        if ($conn->query($query)) {
            $this->id = $conn->insert_id;
            $result = true;
        }
        else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        return $result;

    }

    public static function mostrarPeliculasAlquiladas($idUsuario){
        $conn = Aplicacion::getInstance()->getConexionBd();

        if ($idUsuario != null) {
            $query = sprintf("SELECT * FROM alquiler WHERE idUsuario = '%s'", $conn->real_escape_string($idUsuario));
        }
            
        $rs = $conn->query($query);
        $alquileres = [];
    
        if ($rs->num_rows != 0) {
            while ($fila = $rs->fetch_assoc()) {
                if ($fila) {
                    $alquileres[] = $fila;
                }
            }
        }
        return $alquileres;
    }
    
    public static function estaAlquilado($idUsuario, $idPelicula) {
        
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("SELECT * FROM alquiler A WHERE A.idUsuario='%s' AND A.idPelicula='%s'", 
                            $conn->real_escape_string($idUsuario), $conn->real_escape_string($idPelicula));
        $rs = $conn->query($query);
        $alquilado = false;

        if ($rs->fetch_assoc()) {
            $alquilado = true;
        }

        return $alquilado;
    }

    public static function estaPuntuado($idUsuario, $idPelicula) {
        
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("SELECT puntuacion FROM valoracion V WHERE V.idUsuario='%s' AND V.idPelicula='%s'", 
                            $conn->real_escape_string($idUsuario), $conn->real_escape_string($idPelicula));
        $rs = $conn->query($query);
        $puntuado = false;

        if ($rs->fetch_assoc() != 0) {
            $puntuado = true;
        }

        return $puntuado;
    }

    public static function estaComentado($idUsuario, $idPelicula) {
        
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("SELECT comentario FROM valoracion V WHERE V.idUsuario='%s' AND V.idPelicula='%s'", 
                            $conn->real_escape_string($idUsuario), $conn->real_escape_string($idPelicula));
        $rs = $conn->query($query);
        $comentado = false;

        if ($rs->fetch_assoc() != '') {
            $comentado = true;
        }

        return $comentado;
    }

    public static function darPuntuacion($puntuacion, $idUsuario, $idPelicula) {

        $conn = Aplicacion::getInstance()->getConexionBd();
        $result = false;

        if (self::estaPuntuado($idUsuario, $idPelicula) || self::estaComentado($idUsuario, $idPelicula)) {
            $query = sprintf("UPDATE valoracion SET puntuacion = '%s'
            WHERE idUsuario = '%s' AND idPelicula = '%s'", 
            $conn->real_escape_string($puntuacion), 
            $conn->real_escape_string($idUsuario),
            $conn->real_escape_string($idPelicula));

            $rs = $conn->query($query);
            if ($rs) {
                $result = true;
            }
        }
        else {
            $query = sprintf("INSERT INTO valoracion (idUsuario, idPelicula, puntuacion) VALUES ('%s', '%s', '%s')", 
            $conn->real_escape_string($idUsuario), 
            $conn->real_escape_string($idPelicula),
            $conn->real_escape_string($puntuacion));

            $rs = $conn->query($query);
            if ($rs) {
                $result = true;
                $conn->insert_id;
            }
    
        }

        return $result;
    }

    public static function darComentario($comentario, $idUsuario, $idPelicula) {

        $conn = Aplicacion::getInstance()->getConexionBd();
        $result = false;

        if (self::estaPuntuado($idUsuario, $idPelicula) || self::estaComentado($idUsuario, $idPelicula)) {
            $query = sprintf("UPDATE valoracion SET comentario = '%s'
            WHERE idUsuario = '%s' AND idPelicula = '%s'", 
            $conn->real_escape_string($comentario), 
            $conn->real_escape_string($idUsuario),
            $conn->real_escape_string($idPelicula));

            $rs = $conn->query($query);
            if ($rs) {
                $result = true;
            }
        }
        else {
            $query = sprintf("INSERT INTO valoracion (idUsuario, idPelicula, comentario) VALUES ('%s', '%s', '%s')", 
            $conn->real_escape_string($idUsuario), 
            $conn->real_escape_string($idPelicula),
            $conn->real_escape_string($comentario));

            $rs = $conn->query($query);
            if ($rs) {
                $result = true;
                $conn->insert_id;
            }
    
        }

        return $result;
    }

    public static function eliminarPuntuacion($idUsuario, $idPelicula) {
        $result = false;
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("UPDATE valoracion SET puntuacion = NULL WHERE idUsuario = '%s' AND idPelicula = '%s'", 
                                    $conn->real_escape_string($idUsuario), $conn->real_escape_string($idPelicula));
        if ($conn->query($query)) {
            $result = true;
        }
        else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        return $result;
    }

    public static function eliminarComentario($idUsuario, $idPelicula) {
        $result = false;
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("UPDATE valoracion SET comentario = NULL WHERE idUsuario = '%s' AND idPelicula = '%s'", 
                                    $conn->real_escape_string($idUsuario), $conn->real_escape_string($idPelicula));
        if ($conn->query($query)) {
            $result = true;
        }
        else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        return $result;
    }

    public static function eliminarValoracion($idUsuario, $idPelicula) {
        $result = false;
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("DELETE FROM valoracion WHERE idUsuario = '%s' AND idPelicula = '%s'", 
                                    $conn->real_escape_string($idUsuario), $conn->real_escape_string($idPelicula));
        if ($conn->query($query)) {
            $result = true;
        }
        else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        return $result;
    }
}

?>