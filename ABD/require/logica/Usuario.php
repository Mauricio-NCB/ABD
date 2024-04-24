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
    private $numTarjeta;
    private $fechaTarjeta;
    private $cvvTarjeta;
   
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

    public function getNumTarjeta()
    {
        return $this->numTarjeta;
    }
    public function getCvvTarjeta()
    {
        return $this->cvvTarjeta;
    }

    public function getFechaTarjeta()
    {
        return $this->fechaTarjeta;
    }

    public function getNombreUsuario()
    {
        return $this->nombreUsuario;
    }

    public function getRol()
    {
        return $this->rol;
    }

    private function __construct($id, $correo, $nombreUsuario, $contrasena, $rol, $numTarjeta, $fechaTarjeta, $cvvTarjeta) {
        $this->id = $id;
        $this->correo = $correo;
        $this->nombreUsuario = $nombreUsuario;
        $this->contrasena = $contrasena;
        $this->rol = $rol;
        $this->numTarjeta = $numTarjeta;
        $this->fechaTarjeta = $fechaTarjeta;
        $this->cvvTarjeta = $cvvTarjeta;
    }

    public static function login($nombreUsuario, $contrasena) {
        
        $usuario = self::busca($nombreUsuario);
        if ($usuario && $usuario->comprueba($contrasena)) {
            return true;
        }
        return false;
    }

    public static function registra($correo, $nombreUsuario, $contrasena, $rol, $numTarjeta, $fechaTarjeta, $cvvTarjeta) {
        
        $usuario = new Usuario(null, $correo, $nombreUsuario, self::hash($contrasena), $rol, $numTarjeta, $fechaTarjeta, $cvvTarjeta);
        
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
                            $fila['contrasena'], $fila['rol'], $fila['numTarjeta'],
                            $fila['fechaTarjeta'], $fila['cvvTarjeta']); 
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
        $query = sprintf("INSERT INTO usuario (correo, nombreUsuario, contrasena, rol,
                            numTarjeta, fechaTarjeta, cvvTarjeta) VALUES
                            ('%s', '%s', '%s', '%s', '%s', '%s', '%s')"
                            , $conn->real_escape_string($usuario->correo)
                            , $conn->real_escape_string($usuario->nombreUsuario)
                            , $conn->real_escape_string($usuario->contrasena)
                            , $conn->real_escape_string($usuario->rol)
                            , $conn->real_escape_string($usuario->numTarjeta)
                            , $conn->real_escape_string($usuario->fechaTarjeta)
                            , $conn->real_escape_string($usuario->cvvTarjeta)
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



}

?>