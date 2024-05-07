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
        <div>
            <input type="hidden" name="id" value="$id">
            <button type="submit" class="btn btn-primary" disabled>Alquilado</button>
        </div>
        </form>
        EOF;
    }
    else {
        $botonAlquilar = <<<EOF
        <form method="post" action="alquilar.php">
        <div>
            <input type="hidden" name="id" value="$id">
            <button type="submit" class="btn btn-primary">Alquilar</button>
        </div>
        </form>
        EOF;
    }

    
    //Crear formulario para darValoracion y para darComentario


    $options = '';

    for ($i = 1; $i <= 10; $i++) {
        $options .= '<option value="' .$i. '">' .$i. '</option>';
    }

    $formValorar = <<<EOF
    <form method="post" action="valorar.php">
    <input type="hidden" name="id" value="$id">
    <fieldset>
            <div>
                <label for="puntuacion"> Puntuación: </label>
                <select id="puntuacion" name="puntuacion">
                $options
                </select>
                <button type="submit" name="Valorar">Valorar</button>
            </div>
    </fieldset>
    </form>
    EOF;

    //Buscar forma de usar Formulario pillando el post de la id de Pelicula

    $formComentar = <<<EOF
    <form method="post" action="comentar.php">
    <input type="hidden" name="id" value="$id">
    <fieldset>
            <div>
                <label for="comentario"> Comentario: </label>
                <input id="comentario" type="text" name="comentario"/>
                <button type="submit" name="Comentar">Comentar</button>
            </div>
    </fieldset>
    </form>
    EOF;

    $contenidoPrincipal = <<<EOF
    <div class="producto">
        <h4>$nombre</h4>
        <p>$descripcion</p>
        <p>$precio €</p>

        $botonAlquilar
        $formValorar
        $formComentar
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