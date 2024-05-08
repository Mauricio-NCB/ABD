<?php
require_once 'require/config.php';

use abd\Alquiler as Alquiler;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar si se ha enviado un formulario POST y se ha presionado el botón "Valorar"
    $idPelicula = $_POST['id'];
    $idUsuario = $_SESSION['id'];
    // Recuperar la puntuación seleccionada
    $puntuacion = $_POST['puntuacion'];
    
    // Llamar a la función darValoracion() con la puntuación recuperada
    Alquiler::darValoracion($puntuacion, $idUsuario, $idPelicula);
    header("Location: producto.php?id=" . $idPelicula);
    exit; // Terminar el script después de redirigir
}

?>
