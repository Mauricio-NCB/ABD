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

    private function __construct($id = null, $correo, $nombreUsuario, $contrasena, $rol, $numTarjeta, $fechaTarjeta, $cvvTarjeta) {
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

        return $usuario->comprueba($contrasena);
    }

    public static function registra($correo, $nombreUsuario, $contrasena, $rol, $numTarjeta, $fechaTarjeta, $cvvTarjeta) {
        
        $usuario = new Usuario($correo, $nombreUsuario, self::hash($contrasena), 0, $numTarjeta, $fechaTarjeta, $cvvTarjeta);
        
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
                $usuario = new Usuario($fila['ID'], $fila['Correo'], $fila['Nombre_usuario'], 
                            $fila['Contraseña'], $fila['Rol'], $fila['Número_tarjeta'],
                            $fila['Fecha_tarjeta'], $fila['CVV_tarjeta']); 
            }
            $result->free();
        }
        else {
            error_log("Error BD ({$conn->errno}): $conn->error");
        }
        return $usuario;
    }

    public static function inserta($usuario) {
        $result = false;
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("INSERT INTO usuario (correo, nombreUsuario, contrasena, rol,
                            numTarjeta, fechaTarjeta, cvvTarjeta) VALUES
                            ('%s, '%s', '%s', '%s', '%s, '%s, '%s)"
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
        }
        else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }

        return $result;
    }

    public static function crea($email, $nombreUsuario, $password, $numTarjeta, $fechaTarjeta, $cvvTarjeta)
    {   
        $user = new Usuario(NULL, $email, $nombreUsuario, self::hash($password), 0, $numTarjeta, $fechaTarjeta, $cvvTarjeta);
        $user->añadeRol($rol);
        return $user->guarda();
    }

    private static function hash($contrasena) {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    private function guarda() {
        return self::inserta($this);
    }

    private function comprueba($contrasena) {
        return password_verify($contrasena, $this->contrasena);
    }



}

?>