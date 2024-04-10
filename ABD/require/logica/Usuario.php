<?php

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
        $this->nombreUsuario = $usuario;
        $this->contrasena = $contrasena;
        $this->rol = $rol;
        $this->numTarjeta = $numTarjeta;
        $this->fechaTarjeta = $fechaTarjeta;
        $this->cvvTarjeta = $cvvTarjeta;
    }

}

?>