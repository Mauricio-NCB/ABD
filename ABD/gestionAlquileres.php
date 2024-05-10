<?php 

require_once __DIR__.'/require/config.php';
use abd\Alquiler as Alquiler;
use abd\Pelicula as Pelicula;
use abd\FormularioEliminarPuntuacion as FormularioEliminarPuntuacion;
use abd\FormularioEliminarComentario as FormularioEliminarComentario;

$tituloPagina = "Lista de Peliculas Alquiladas por Usuarios";

$contenidoPrincipal = "";

if (! isset($_SESSION['esAdmin']) || !$_SESSION['esAdmin']) {
	header("Location:indice.php");
}
else {

    $contenidoPrincipal = "<h1>Estas son las películas alquiladas por todos los usuarios.</h1>";

    $peliculasAlquiladas = Alquiler::mostrarPeliculasAlquiladas();

    if(!empty($peliculasAlquiladas)){
        foreach ($peliculasAlquiladas as $alquiler) {
            $pelicula = Pelicula::buscarPelicula($alquiler['idPelicula']);        
            $puntuacion = Pelicula::obtenerValoracion($pelicula['Id'], $alquiler['idUsuario']);
            $comentario = Pelicula::obtenerComentarios($pelicula['Id'], $alquiler['idUsuario']);

            $formDelPuntuacion = new FormularioEliminarPuntuacion($alquiler['idUsuario'], $pelicula['Id']);
            $htmlFormPunt = $formDelPuntuacion->gestiona();

            $formDelComentario = new FormularioEliminarComentario($alquiler['idUsuario'], $pelicula['Id']);
            $htmlFormCom = $formDelComentario->gestiona();

            $contenidoPrincipal .= <<< EOS
                <p>Nombre de la película: {$pelicula['Nombre']}</p>
                <p>Descripción de la película: {$pelicula['Descripcion']}</p>
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
    } else {
        $contenidoPrincipal .= "<p>No hay ninguna película alquilada.</p>";
    }
}
require_once('require/vistas/plantillas/plantilla.php');

?>
