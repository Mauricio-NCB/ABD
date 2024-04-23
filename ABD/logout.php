<?php 
require_once __DIR__."/require/config.php";
use abd\FormularioLogout as FormularioLogout;

$tituloPagina = "Logout";

$formLogout = new FormularioLogout();
$htmlForm = $formLogout->gestiona();

$contenidoPrincipal = <<<EOS
        <div class="contenedor">
       
        <p> Gracias por visitar nuestra web. Hasta pronto. </p>

        </div> 
    EOS;

require_once __DIR__."/includes/vistas/plantillas/plantilla.php";