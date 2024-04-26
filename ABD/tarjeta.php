<?php
require_once 'require/config.php';
use abd\FormularioAñadirTarjeta as FormularioAñadirTarjeta;

$tituloPagina = 'Añade Metodo de Pago';

$formLogin = new FormularioAñadirTarjeta();
$htmlFormLogin = $formLogin->gestiona();

$contenidoPrincipal=<<<EOS
    <h1>Acceso al sistema</h1>
    $htmlFormLogin
EOS;

require __DIR__.'/require/vistas/plantillas/plantilla.php';

?>