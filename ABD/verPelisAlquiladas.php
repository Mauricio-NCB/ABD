<?php 

require_once __DIR__.'/require/config.php';
use abd\Alquiler as Alquiler;
use abd\Pelicula as Pelicula;
use abd\Aplicacion as Aplicacion;

$tituloPagina = "Lista de Peliculas Alquiladas";

$contenidoPrincipal = "<h1>Estás son tus páginas alquiladas.</h1>";

$conn = Aplicacion::getInstance()->getConexionBd();
$peliculas = Alquiler::mostrarPeliculasAlquiladas($_SESSION['id']);
$i=0;
if(count($peliculas) != 0){
    while ($i != count($peliculas)){
        $pelis = Pelicula::buscarPelicula($peliculas[$i]);
        $contenidoPrincipal .= <<< EOS
            <p>Nombre de la pelicula: {$pelis['Nombre']}</p>
            <p>Descripcion de la pelicula: {$pelis['Descripcion']}</p>
            <p>Precio del alquiler: {$pelis['Precio']}</p> 
            EOS;
        $query2 = sprintf("SELECT puntuacion , comentario FROM valoracion WHERE idPelicula = '$pelis[Id]'"); 
        $result2 = $conn->query($query2);
        $row2 = $result2->fetch_array();
        if($result2->num_rows!=0){
            $contenidoPrincipal .= <<< EOS
                <p>Valoración sobre la película: {$row2['puntuacion']} </p>
                <p>Comentario sobre la película: {$row2['comentario']} </p>
            EOS;
        }
        
        $contenidoPrincipal .= <<< EOS
        <p>-------------------------------------</p>
        EOS;
        
        $i++;
        
    }
    $result2->free();
}
else{
    $contenidoPrincipal .= "No hay ninguna pelicula alquilada.";
}
require_once('require/vistas/plantillas/plantilla.php');

?>