<?php
require_once __DIR__."/require/config.php";
require_once __DIR__."/require/selUsuario.php";

$tituloPagina = 'Gestión de alquileres';
$paginaRedireccion = 'alquilerElegido';

$contenidoPrincipal = generarContenidoPrincipal($tituloPagina, $paginaRedireccion);

require __DIR__.'/require/vistas/plantillas/plantilla.php';