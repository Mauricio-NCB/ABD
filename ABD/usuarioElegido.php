<?php
require_once __DIR__."/require/config.php";

use abd\FormularioDarAdmin as FormularioDarAdmin;

$tituloPagina = 'Dar administrador';

$contenidoPrincipal = '';

if (! isset($_SESSION['esAdmin']) || !$_SESSION['esAdmin']) {
	header("Location:indice.php");
}
else {
	$formDarAdmin = new FormularioDarAdmin();
	$htmlForm = $formDarAdmin->gestiona();

    $contenidoPrincipal = <<<EOS
	<h1>Gestión de usuarios</h1>
	$htmlForm
	EOS;
}

require __DIR__.'/require/vistas/plantillas/plantilla.php';