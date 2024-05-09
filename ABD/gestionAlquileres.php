<?php
//Encargado de la gestión de los alquileres hechos por los usuarios 
//Muestra alquileres realizados y permite eliminar comentarios o valoraciones
require_once __DIR__."/require/config.php";
use abd\Alquiler as Alquiler;
use abd\Aplicacion as Aplicacion;

$tituloPagina = 'Gestión de alquileres';

$contenidoPrincipal = '';

if (! isset($_SESSION['esAdmin']) || !$_SESSION['esAdmin']) {
	header("Location:indice.php");
}
else {

    $peliculas = 

	$contenidoPrincipal = <<<EOS
	<h1>Gestión de alquileres</h1>
	<p>Permite ver alquileres y eliminar comentarios o valoraciones</p>
	<div id="Boton de administracion">
	<form action='anadirPelicula.php'>
		<button type='submit' name="submit" id="submit">Añadir película</button>
	</form>
	</div> 
	<div id="Boton de administracion">
	<form action='modificarPelicula.php'>
		<button type='submit' name="submit" id="submit">Modificar película</button>
	</form>
	</div>
	<div id="Boton de administracion">
	<form action='eliminarPelicula.php'>
		<button type='submit' name="submit" id="submit">Eliminar película</button>
	</form>
	</div>
	EOS;
}

require __DIR__.'/require/vistas/plantillas/plantilla.php';
?>
