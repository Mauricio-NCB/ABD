<?php

require_once 'require/config.php';
use abd\Pelicula as Pelicula;
use abd\Alquiler as Alquiler;

$tituloPagina = "Vista de la pelicula";

$pelicula = Pelicula::mostrarPelicula($_REQUEST["id"]);

if ($pelicula != null) {

    $id = $pelicula["Id"];
    $nombre = $pelicula["Nombre"];
    $descripcion = $pelicula["Descripcion"];
    $precio = $pelicula["Precio"];

    //Funcion para cargar valoraciones y comentarios de la tabla de usuarios
    if (Alquiler::estaAlquilado($_SESSION['id'], $id)) {
        $botonAlquilar = <<<EOF
        <form method="post" action="alquilar.php">
            <input type="hidden" name="id" value="$id">
            <button type="submit" class="btn btn-primary" disabled>Alquilado</button>
        </form>
        EOF;
    }
    else {
        $botonAlquilar = <<<EOF
        <form method="post" action="alquilar.php">
            <input type="hidden" name="id" value="$id">
            <button type="submit" class="btn btn-primary">Alquilar</button>
        </form>
        EOF;
    }



    $contenidoPrincipal = <<<EOF
    <div class="producto">
        <h4>$nombre</h4>
        <p>$descripcion</p>
        <p>$precio €</p>

        $botonAlquilar

        <a href="nuevaValoracion.php?id=$id">Valorar producto</a>

    </div>
    EOF;
}
else {
    $contenidoPrincipal =<<<EOF
        <h4>Producto no visible</h4>
        <p>Ha ocurrido un problema y no le podemos mostrar la información del producto</p>
    EOF;
}



require_once __DIR__.'/require/vistas/plantillas/plantilla.php';