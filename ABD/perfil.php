<?php

require_once __DIR__.'/require/config.php';

$tituloPagina="Página de usuario";
$contenidoPrincipal="<h1>Bienvenido Usuario</h1>
<a href='registro.php'>Registro</a><br>
<a href='login.php'>Login</a><br>";
if(isset($_SESSION['login']) && $_SESSION['login']){
    $contenidoPrincipal.="
    <a href='tarjeta.php'>Tarjeta</a><br>";
}
if(isset($_SESSION['login']) && $_SESSION['login']){
    $contenidoPrincipal.="
    <a href ='logout.php'>Cerrar Sesión.</a>";
}

require_once('require/vistas/plantillas/plantilla.php');

/*echo
"<h1>Bienvenido Usuario</h1>
<a href='registro.php'>Registro</a><br>
<a href='login.php'>Login</a>";
*/
?>