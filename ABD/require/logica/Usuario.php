<?php

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

    private function __construct($id = null, $correo, $nombreUsuario, $contrasena, $rol = null, $numTarjeta, $fechaTarjeta, $cvvTarjeta) {
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
        
        $usuario = new Usuario($correo, $nombreUsuario, self::hash($contrasena), $rol, $numTarjeta, $fechaTarjeta, $cvvTarjeta);
        
        return $usuario->guarda();
    }

    private static function busca($nombreUsuario) {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("SELECT * FROM usuarios U WHERE U.NombreUsuario='%s'", $conn->real_escape_string($nombreUsuario));
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

    private static function inserta($usuario) {
        $result = false;
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("INSERT INTO Usuarios(Correo, NombreUsuario, Contraseña, Rol,
                            Número_tarjeta, Fecha_tarjeta, CVV_tarjeta) VALUES
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