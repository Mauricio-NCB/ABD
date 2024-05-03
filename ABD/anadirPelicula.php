<?php
require_once 'require/config.php';
use abd\FormularioAnadirPelicula as FormularioAnadirPelicula;

$tituloPagina = 'Añadir Película';

$contenidoPrincipal = '';

if (! isset($_SESSION['esAdmin']) || !$_SESSION['esAdmin']) {
	header("Location:indice.php");
}
else {
    $formAdd = new FormularioAnadirPelicula();
    $htmlForm = $formAdd->gestiona();

    $contenidoPrincipal=<<<EOS
        <h1>Añadir películas</h1>
        $htmlForm
    EOS;

    require __DIR__.'/require/vistas/plantillas/plantilla.php';
}
?>