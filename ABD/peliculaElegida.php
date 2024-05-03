<?php
require_once __DIR__."/require/config.php";

use abd\FormularioModificarPelicula as FormularioModificarPelicula;


$tituloPagina = 'Modificar Película';

$contenidoPrincipal = '';

if (! isset($_SESSION['esAdmin']) || !$_SESSION['esAdmin']) {
	header("Location:indice.php");
}
else {
	$formMod = new FormularioModificarPelicula();
	$htmlForm = $formMod->gestiona();

    $contenidoPrincipal = <<<EOS
	<h1>Gestión de películas</h1>
	$htmlForm
	EOS;
}

require __DIR__.'/require/vistas/plantillas/plantilla.php';