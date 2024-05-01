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
    <a href= 'editaPerfil.php'>Edita tu perfil</a><br>
    <a href ='logout.php'>Cerrar Sesión</a><br>";
}
if(isset($_SESSION['esAdmin']) && $_SESSION['esAdmin']){
    $contenidoPrincipal.="
    <a href ='administracion.php'>Eres Administrador</a>";
}
else{
    $contenidoPrincipal.="
    Bienvenido Usuario";
}

require_once('require/vistas/plantillas/plantilla.php');

/*echo
"<h1>Bienvenido Usuario</h1>
<a href='registro.php'>Registro</a><br>
<a href='login.php'>Login</a>";
*/
?>