<?php
require_once __DIR__.'/require/config.php';

use abd\Aplicacion as Aplicacion;
use abd\FormularioEditarPerfil as FormularioEditarPerfil;
use abd\Usuario as Usuario;

$tituloPagina = 'Mi perfil';

$formEdit = new FormularioEditarPerfil();
$htmlForm = $formEdit->gestiona();

$contenidoPrincipal=<<<EOS
<h1>Datos de mi perfil</h1>    

<div class="contenedor">
     $htmlForm   
</div>

EOS;

require __DIR__.'/require/vistas/plantillas/plantilla.php';


?>