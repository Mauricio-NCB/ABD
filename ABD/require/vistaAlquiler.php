<?php 

use abd\Alquiler as Alquiler;
use abd\Pelicula as Pelicula;
use abd\FormularioEliminarPuntuacion as FormularioEliminarPuntuacion;
use abd\FormularioEliminarComentario as FormularioEliminarComentario;

function generarContenidoPrincipal($idUsuario, $subtitulo) {

    $contenidoPrincipal = $subtitulo;

    $alquileres = Alquiler::mostrarPeliculasAlquiladas($idUsuario);
    
    if(count($alquileres) != 0){
        foreach ($alquileres as $alquiler) {
            $pelicula = Pelicula::buscarPelicula($alquiler['idPelicula']);        

            $formDelPuntuacion = new FormularioEliminarPuntuacion($alquiler['idUsuario'], $pelicula['Id']);
            $htmlFormPunt = $formDelPuntuacion->gestiona();
    
            $formDelComentario = new FormularioEliminarComentario($alquiler['idUsuario'], $pelicula['Id']);
            $htmlFormCom = $formDelComentario->gestiona();
    
            $puntuacion = Pelicula::obtenerValoracion($pelicula['Id'], $idUsuario);
            $comentario = Pelicula::obtenerComentarios($pelicula['Id'], $idUsuario);


            $contenidoPrincipal .= <<< EOS
                <p>Nombre de la pelicula: <a href="producto.php?id=$pelicula[Id]"> {$pelicula['Nombre']} </a> </p>
                <p>Descripcion de la pelicula: {$pelicula['Descripcion']}</p>
                <p>Precio del alquiler: {$pelicula['Precio']}</p>            
                <p>Fecha de Inicio del alquiler: {$alquiler['fechaInicio']}</p>
                <p>Fecha de Fin del alquiler: {$alquiler['fechaFin']}</p> 
                EOS;
    
            if (!empty($puntuacion)) {
                $contenidoPrincipal .= "<p>Puntuación sobre la película: {$puntuacion}</p> $htmlFormPunt";
            } else {
                $contenidoPrincipal .= "<p>No hay puntuación sobre esta película.</p>";
            }
    
            if (!empty($comentario)) {
                $contenidoPrincipal .= "<p>Comentario sobre la película: {$comentario[0]}</p> $htmlFormCom";
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

    return $contenidoPrincipal;
}


?>