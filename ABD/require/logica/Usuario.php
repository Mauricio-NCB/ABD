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

    public static function listaUsuarios() {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("SELECT * FROM usuario U ");
        $rs = $conn->query($query);
        $result = [];
        $i = 0;
        if ($rs) {
            foreach ($rs as $fila) {  
                $result[$i] = new Usuario($fila['id'], $fila['correo'], $fila['nombreUsuario'], 
                            $fila['contrasena'], $fila['rol']); 
                $i++;
            }
            $rs->free();
        }
        else {
            error_log("Error BD ({$conn->errno}): $conn->error");
        }
        return $result;
    }

    public static function listaIdsUsuarios() {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("SELECT id FROM usuario U ");
        $rs = $conn->query($query);
        $result = [];

        if ($rs) {
            foreach ($rs as $fila) {  
                $result[] = $fila['id']; 
            }
            $rs->free();
        }
        else {
            error_log("Error BD ({$conn->errno}): $conn->error");
        }
        return $result;
    }

    public static function tieneTarjeta($idU) {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("SELECT * FROM tarjeta T WHERE T.idUsuario = '%s'" , $conn->real_escape_string($idU));
        $result = $conn->query($query);
        if ($result->num_rows > 0) {
            return true;
        }
        return false;
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

    public static function darAdmin($correoUser) {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $correoUsuario = $conn->real_escape_string($correoUser);
        $query = sprintf("UPDATE usuario SET rol = '1' WHERE correo='$correoUsuario'");
        if ( !$conn->query($query) ) {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
            return false;
        }
        return true;
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

    public static function editarDatos($email_usuario, $nombreUsuario,$password,$rol){
        
        $result = false;
        $conn = Aplicacion::getInstance()->getConexionBd();
        $pass = self::hash($password);
        $query=sprintf("UPDATE usuario SET nombreUsuario = '$nombreUsuario', contrasena='$pass', rol ='$rol' 
            WHERE correo= '$email_usuario'");

            $rs = $conn->query($query);
        if ($rs) {
            $result = true;
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        
        return $result;

    }
}

?>