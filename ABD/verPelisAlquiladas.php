<?php 

require_once __DIR__.'/require/config.php';
use abd\Alquiler as Alquiler;
use abd\Pelicula as Pelicula;

$tituloPagina = "Lista de Peliculas Alquiladas";

$contenidoPrincipal = "<h1>Estás son tus páginas alquiladas.</h1>";

$alquileres = Alquiler::mostrarPeliculasAlquiladas($_SESSION['id']);

if(count($alquileres) != 0){
    foreach ($alquileres as $alquiler) {
        $pelicula = Pelicula::buscarPelicula($alquiler['idPelicula']);        
        $puntuacion = Pelicula::obtenerValoracion($pelicula['Id'], $_SESSION['id']);
        $comentario = Pelicula::obtenerComentarios($pelicula['Id'], $_SESSION['id']);

        $contenidoPrincipal .= <<< EOS
            <p>Nombre de la pelicula: {$pelicula['Nombre']}</p>
            <p>Descripcion de la pelicula: {$pelicula['Descripcion']}</p>
            <p>Precio del alquiler: {$pelicula['Precio']}</p>            
            <p>Fecha de Inicio del alquiler: {$alquiler['fechaInicio']}</p>
            <p>Fecha de Fin del alquiler: {$alquiler['fechaFin']}</p> 
            EOS;

        if (!empty($puntuacion) && $puntuacion != 0) {
            $contenidoPrincipal .= "<p>Puntuación sobre la película: {$puntuacion}</p>$htmlFormPunt";
        } else {
            $contenidoPrincipal .= "<p>No hay puntuación sobre esta película.</p>";
        }

        if (!empty($comentario)) {
            $contenidoPrincipal .= "<p>Comentario sobre la película: {$comentario[0]}</p>$htmlFormCom";
        } else {
            $contenidoPrincipal .= "<p>No hay comentarios sobre esta película.</p>";
        }

        $contenidoPrincipal .= <<< EOS
            <p>-------------------------------------</p>
        EOS;
    }
}
else{
    $contenidoPrincipal .= "No hay ninguna pelicula alquilada.";
}
require_once('require/vistas/plantillas/plantilla.php');

?>