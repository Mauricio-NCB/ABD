<?php

require_once 'require/config.php';
use abd\FormularioDarComentario as FormularioDarComentario;
use abd\Pelicula as Pelicula;
use abd\Alquiler as Alquiler;


$tituloPagina = "Vista de la pelicula";

$pelicula = Pelicula::buscarPelicula($_REQUEST["id"]);

if ($pelicula != null) {

    $id = $pelicula["Id"];
    $nombre = $pelicula["Nombre"];
    $descripcion = $pelicula["Descripcion"];
    $precio = $pelicula["Precio"];
    $valoracion = Pelicula::obtenerValoracion($id);
    $comentarios = Pelicula::obtenerComentarios($id);

    //Funcion para cargar valoraciones y comentarios de la tabla de usuarios
    if (Alquiler::estaAlquilado($_SESSION['id'], $id)) {
        $botonAlquilar = <<<EOF
        <fieldset>
            <div>
                <input type="hidden" name="id" value="$id">
                <button type="submit" class="btn btn-primary" disabled>Gracias por tu compra</button>
            </div>
        </fieldset>
        EOF;
    }
    else {
        $botonAlquilar = <<<EOF
        <form method="post" action="alquilar.php">
        <fieldset>
            <div>
                <label for="alquiler"> Ahora en tu pantalla por $precio €</label>
                <input type="hidden" name="id" value="$id">
                <button type="submit" class="btn btn-primary">Alquilar</button>
            </div>
        </fieldset>
        </form>
        EOF;
    }

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

    $formComentar = new FormularioDarComentario($id);
    $htmlFormComentar = $formComentar->gestiona();

    $listaComentarios = "<h3>Comentarios</h3>";
    if ($comentarios != null) {
        foreach($comentarios as $comentario) {

            $listaComentarios .= <<<EOF
            <fieldset>
            <div class="valoracion">
            <h4>Usuario registrado</h4>
            <p>$comentario</p>
            </div>
            </fieldset>
            EOF;
        }
    }
    else {
        $listaComentarios = <<<EOF
        <h4>No hay comentarios del producto</h4>
    EOF;
    }

    $contenidoPrincipal = <<<EOF
    <div class="producto">
        <h2>$nombre</h2>
        <p>$descripcion</p>
        <p>Puntuación $valoracion/10</p>

        $botonAlquilar
        $formValorar
        $htmlFormComentar

        <p>$listaComentarios</p>
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