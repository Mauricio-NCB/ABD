<?php
require_once 'require/config.php';
use abd\FormularioEliminarPelicula as FormularioEliminarPelicula;

$tituloPagina = 'Eliminar película';

$contenidoPrincipal = '';

if (! isset($_SESSION['esAdmin']) || !$_SESSION['esAdmin']) {
	header("Location:indice.php");
}
else {
    $formDel = new FormularioEliminarPelicula();
    $htmlForm = $formDel->gestiona();

    $contenidoPrincipal=<<<EOS
        <h1>Eliminar películas</h1>
        $htmlForm
    EOS;

    require __DIR__.'/require/vistas/plantillas/plantilla.php';
}
?>
