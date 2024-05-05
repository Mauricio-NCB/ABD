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
}

?>