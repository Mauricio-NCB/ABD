<?php

namespace abd;
//Tabla Usuario. Hecha para almacenar los datos de cada Usuario creado y logueado en nuestra página web.

class Usuario {
    public const ADMIN_ROLE = 1;
    public const USER_ROLE = 0;

    private $id;
    private $correo;
    private $nombreUsuario;
    private $contrasena;
    private $rol;
   
    public function getEmail()
    {
        return $this->correo;
    }

    public function getPassword()
    {
        return $this->contrasena;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getNombreUsuario()
    {
        return $this->nombreUsuario;
    }

    public function getRol()
    {
        return $this->rol;
    }

    private function __construct($id, $correo, $nombreUsuario, $contrasena, $rol) {
        $this->id = $id;
        $this->correo = $correo;
        $this->nombreUsuario = $nombreUsuario;
        $this->contrasena = $contrasena;
        $this->rol = $rol;
    }

    public static function login($nombreUsuario, $contrasena) {
        
        $usuario = self::busca($nombreUsuario);
        
        if ($usuario && $usuario->comprueba($contrasena)) {
            return $usuario;
        }
        
        return false;
    }

    public static function registra($correo, $nombreUsuario, $contrasena, $rol) {
        
        $usuario = new Usuario(null, $correo, $nombreUsuario, self::hash($contrasena), $rol);
        
        return $usuario->guarda();
    }

    public static function busca($nombreUsuario) {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("SELECT * FROM usuario U WHERE U.nombreUsuario='%s'", $conn->real_escape_string($nombreUsuario));
        $result = $conn->query($query);
        $usuario = false;
        if ($result) {
            $fila = $result->fetch_assoc();
            if ($fila) {
                $usuario = new Usuario($fila['id'], $fila['correo'], $fila['nombreUsuario'], 
                            $fila['contrasena'], $fila['rol']); 
            }
            $result->free();
        }
        else {
            error_log("Error BD ({$conn->errno}): $conn->error");
        }
        return $usuario;
    }

    public static function buscaTarjeta($tarjeta, $idU) {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("SELECT * FROM tarjeta T WHERE T.numeroTarjeta='%s' AND T.idUsuario = '%s'" , $conn->real_escape_string($tarjeta), $conn->real_escape_string($idU));
        $result = $conn->query($query);
        if ($result->num_rows > 0) {
            return true;
        }
        return false;
    }


    public static function buscaPorCorreo($correo) {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("SELECT * FROM usuario U WHERE U.correo='%s'", $conn->real_escape_string($correo));
        $result = $conn->query($query);
        $usuario = false;
        if ($result) {
            $fila = $result->fetch_assoc();
            if ($fila) {
                $usuario = new Usuario($fila['id'], $fila['correo'], $fila['nombreUsuario'], 
                            $fila['contrasena'], $fila['rol']); 
            }
            $result->free();
        }
        else {
            error_log("Error BD ({$conn->errno}): $conn->error");
        }
        return $usuario;
    }

    private static function inserta($usuario) {
        $result = false;
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("INSERT INTO usuario (correo, nombreUsuario, contrasena, rol) VALUES
                            ('%s', '%s', '%s', '%s')"
                            , $conn->real_escape_string($usuario->correo)
                            , $conn->real_escape_string($usuario->nombreUsuario)
                            , $conn->real_escape_string($usuario->contrasena)
                            , $conn->real_escape_string($usuario->rol)
                        );
        if ($conn->query($query)) {
            $usuario->id = $conn->insert_id;
            $result = $usuario;
        }
        else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }

        return $result;
    }

    private static function hash($contrasena) {
        return password_hash($contrasena, PASSWORD_DEFAULT);
    }

    private function guarda() {
        return self::inserta($this);
    }

    private function comprueba($contrasena) {
        return password_verify($contrasena, $this->contrasena);
    }

    public static function registraTarjeta($numtarjeta,$fechatarjeta,$cvvtarjeta){
        $result = false;
        $conn = Aplicacion::getInstance()->getConexionBd();
        $usuario = self::busca($_SESSION['nombreusuario']);
        $query = sprintf("INSERT INTO tarjeta (idUsuario, numeroTarjeta, fechaTarjeta, cvvTarjeta) VALUES
                            ('%s', '%s', '%s', '%s')"
                            , $conn->real_escape_string($usuario->id)
                            , $conn->real_escape_string($numtarjeta)
                            , $conn->real_escape_string($fechatarjeta)
                            , $conn->real_escape_string($cvvtarjeta)
                        );
        if (!$conn->query($query)) {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
    }
}

?>