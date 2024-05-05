<?php 

require_once __DIR__.'/require/config.php';
use abd\Usuario as Usuario;

$tituloPagina = "Lista de Peliculas Alquiladas";

$contenidoPrincipal = "<h1>Estás son tús páginas alquiladas.</h1>";

$peliculas = Usuario::mostrarPeliculasAlquiladas($_SESSION['id']);

require_once('require/vistas/plantillas/plantilla.php');

?>