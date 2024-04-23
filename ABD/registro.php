<?php
require_once 'require/config.php';
use abd\FormularioRegistro as FormularioRegistro;

$tituloPagina = 'Registro';

$formReg = new FormularioRegistro();
$htmlForm = $formReg->gestiona();

$contenidoPrincipal=<<<EOS
    <h1>Registro a la Página Web</h1>
    $htmlForm
EOS;

require __DIR__.'/require/vistas/plantillas/plantilla.php';

?>