<?php
require_once 'require/config.php';

use abd\Alquiler as Alquiler;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['Comentar'])) {
    // Verificar si se ha enviado un formulario POST y se ha presionado el botón "Comentar"
    $idPelicula = $_POST['id'];
    $idUsuario = $_SESSION['id'];
    // Recuperar el comentario seleccionado
    $comentario = $_POST['comentario'];
    
    // Llamar a la función darComentario() con el comentario recuperado
    Alquiler::darComentario($comentario, $idUsuario, $idPelicula);
    header("Location: producto.php?id=" . $idPelicula);
    exit; // Terminar el script después de redirigir
}

?>