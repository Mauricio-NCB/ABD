<?php

use abd\FormularioSeleccionarUsuario as FormularioSeleccionarUsuario;

function generarContenidoPrincipal($tituloPagina, $paginaRedireccion) {
    if (!isset($_SESSION['esAdmin']) || !$_SESSION['esAdmin']) {
        header("Location: indice.php");
    } 
    else {
        $formModUsuario = new FormularioSeleccionarUsuario($paginaRedireccion);
        $htmlForm = $formModUsuario->gestiona();

        return <<<EOS
            <h1>$tituloPagina</h1>
            $htmlForm
        EOS;
    }
}


