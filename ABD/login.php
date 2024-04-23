<?php
require_once 'require/config.php';
use abd\FormularioLogin as FormularioLogin;

$tituloPagina = 'Login';

$formLogin = new FormularioLogin();
$htmlFormLogin = $formLogin->gestiona();

$contenidoPrincipal=<<<EOS
    <h1>Acceso al sistema</h1>
    $htmlFormLogin
EOS;

require __DIR__.'/require/vistas/plantillas/plantilla.php';

?>