<?php 

require_once __DIR__.'/require/config.php';
use abd\Usuario as Usuario;
use abd\Aplicacion as Aplicacion;

$tituloPagina = "Lista de Peliculas Alquiladas";

$contenidoPrincipal = "<h1>Estás son tús páginas alquiladas.</h1>";

$peliculas = Usuario::mostrarPeliculasAlquiladas($_SESSION['id']);
$i=0;
$conn = Aplicacion::getInstance()->getConexionBd();
if(count($peliculas) != 0){
    while ($i != count($peliculas)){
        $query = sprintf("SELECT * FROM pelicula WHERE id = '$peliculas[$i]'");     
        $result = $conn->query($query);
        $row=$result->fetch_array();
        $contenidoPrincipal .= <<< EOS
            <p>Nombre de la pelicula: {$row['nombre']}</p>
            <p>Descripcion de la pelicula: {$row['descripcion']}</p>
            <p>Precio del alquiler: {$row['precio']}</p> 
            <p>-------------------------------------</p>
            EOS;
        $i++;
    }
}
else{
    $contenidoPrincipal .= "No hay ninguna pelicula alquilada.";
}
require_once('require/vistas/plantillas/plantilla.php');

?>