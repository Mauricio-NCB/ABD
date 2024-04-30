<?php
require_once __DIR__."/require/config.php";


$tituloPagina = 'Administración';

$contenidoPrincipal = '';

if (! isset($_SESSION['esAdmin']) || !$_SESSION['esAdmin']) {
	header("Location:indice.php");
} 
else {
	$contenidoPrincipal .= <<<EOS
	<h1>Consola de administración</h1>
	<form action='gestionUsuarios.php'>
		<button type='submit' name="submit" id="submit">Gestión de usuarios</button>
	</form>
	EOS;
}

require __DIR__.'/require/vistas/plantillas/plantilla.php';