<?php

require_once __DIR__.'/require/config.php';

$tituloPagina="Mi perfil";

$contenidoPrincipal="<h1>Bienvenido a tu perfil</h1>";

if(isset($_SESSION['login']) && $_SESSION['login']){
    $contenidoPrincipal.="
    <a href='tarjeta.php'>Tarjeta</a><br>";
}
if(isset($_SESSION['login']) && $_SESSION['login']){
    $contenidoPrincipal.="<a href= 'editaPerfil.php'>Edita tu perfil</a><br>";
}

require_once('require/vistas/plantillas/plantilla.php');
?>