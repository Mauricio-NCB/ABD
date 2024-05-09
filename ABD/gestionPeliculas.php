<?php
//Encargado de manejar la gestión de peliculas 
//Añadir peliculas, eliminarlas o modificarlas si es necesario
require_once __DIR__."/require/config.php";

$tituloPagina = 'Gestión de usuarios';

$contenidoPrincipal = '';

if (! isset($_SESSION['esAdmin']) || !$_SESSION['esAdmin']) {
	header("Location:indice.php");
}
else {
	$contenidoPrincipal = <<<EOS
	<h1>Gestión de Películas</h1>
	<p>Permite añadir, eliminar y editar peliculas</p>
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