<?php
require_once 'require/config.php';

use abd\Alquiler as Alquiler;

// Verifica si se ha enviado un formulario POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener el ID del producto desde el formulario
    $idPelicula = $_POST['id'];
    $idUsuario = $_SESSION['id'];
    $fechaActual = date("Y-m-d"); // Formato adecuado para strtotime()
    $fechaDestino = date("Y-m-d", strtotime("+1 month", strtotime($fechaActual)));

    // Llamar a la función nuevoAlquiler
    Alquiler::nuevoAlquiler($idUsuario, $idPelicula, $fechaActual, $fechaDestino);

    // Redirigir a la página de producto u otra página según sea necesario
    header("Location: producto.php?id=" . $idPelicula);
    exit; // Terminar el script después de redirigir
}
