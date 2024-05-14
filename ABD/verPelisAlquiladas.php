<?php 

require_once __DIR__.'/require/config.php';
require_once __DIR__.'/require/vistaAlquiler.php';

$tituloPagina = "Lista de Peliculas Alquiladas";
$subtitulo = "<div><h1>Estás son tus películas alquiladas<h1></div>";

$contenidoPrincipal = generarContenidoPrincipal($_SESSION['id'], $subtitulo);

require_once('require/vistas/plantillas/plantilla.php');

?>