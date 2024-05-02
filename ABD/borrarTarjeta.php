<?php
namespace abd;
use abd\Aplicacion as Aplicacion;
require_once __DIR__.'/require/config.php';
//Definicion de constantes
//Parametros de acceso de la base de datos

    $conn = Aplicacion::getInstance()->getConexionBd();

        $id= $conn->real_escape_string($_POST['idUser']);
        $nt= $conn->real_escape_string($_POST['numTarjeta']);

        $query = "DELETE FROM tarjeta WHERE numeroTarjeta = '$nt' AND idUsuario = '$id' ";
        
        if ( !$conn->query($query) ) {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
            return false;
        }
    
    header("Location: editaPerfil.php");
?>