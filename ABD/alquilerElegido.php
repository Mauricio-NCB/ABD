<?php 

require_once __DIR__.'/require/config.php';
require_once __DIR__.'/require/vistaAlquiler.php';

$id = $_POST['idUsuario'] ?? $_REQUEST['idUsuario'] ?? null;

$tituloPagina = "Administrar Peliculas Alquiladas";
$subtitulo = "<div><h1>Estás son las películas alquiladas del usuario " .$id. "<h1></div>";

$contenidoPrincipal = generarContenidoPrincipal($id, $subtitulo);

require_once('require/vistas/plantillas/plantilla.php');

?>