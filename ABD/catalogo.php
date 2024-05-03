<?php

require_once 'require/config.php';
use abd\Pelicula as Pelicula;

$tituloPagina = 'Catalogo de peliculas';

$peliculas = Pelicula::mostrarCatalogo();
$htmlCatalogo = "";

if ($peliculas != null) {
    foreach($peliculas as $pelicula){
        $id = $pelicula->getId();
        $nombre = $pelicula->getNombre(); 
        $descripcion = $pelicula->getDescripcion(); 
        $precio = $pelicula->getPrecio(); 
        $valoracion = $pelicula->getValoracion(); 
        echo $pelicula->getValoracion();

        $htmlCatalogo .= <<<EOF
        <a href="producto.php?id=$id">
        <div class="producto">
            <h4>$nombre</h4>
            <p>$descripcion</p>
            <p>$precio €</p>   
        </div>
        </a>
        EOF;
    }
}
else{
    $htmlCatalogo = <<<EOF
         <h4>Catalogo vacío</h4>
    EOF;
}

$contenidoPrincipal=<<<EOS
<h1>Catálogo de LibertyClothing</h1>    
<div class="contenedor">
     <div class="catalogo">
     $htmlCatalogo
     </div>
</div>

EOS;

require_once __DIR__.'/require/vistas/plantillas/plantilla.php';

?>