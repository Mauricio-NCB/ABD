<?php
require_once __DIR__.'/require/config.php';

use abd\Aplicacion as Aplicacion;
use abd\FormularioSeleccionarPelicula as FormularioSeleccionarPelicula;
use abd\Pelicula as Pelicula;

$tituloPagina = 'Modificar película';

$contenidoPrincipal = '';

if (! isset($_SESSION['esAdmin']) || !$_SESSION['esAdmin']) {
	header("Location:indice.php");
}
else {
    $formSel = new FormularioSeleccionarPelicula();
    $htmlForm = $formSel->gestiona();

    $contenidoPrincipal=<<<EOS
        <h1>Modificar películas</h1>
        $htmlForm
    EOS;

    require __DIR__.'/require/vistas/plantillas/plantilla.php';
}

?>