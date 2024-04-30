<?php
namespace abd;

use abd\Formulario;

class FormularioLogout extends Formulario {

    public function __construct() {
        parent::__construct('formLogout', ['action' => 'logout.php', 'urlRedireccion' => 'indice.php']);
    }

    protected function generaCamposFormulario(&$datos)
    {
        $camposFormulario = <<<EOS
            <button class="enlace" type="submit">Salir</button>
        EOS;
        return $camposFormulario;
    }

    /**
     * Procesa los datos del formulario.
     */
    protected function procesaFormulario(&$datos)
    {
        unset($_SESSION['login']);
        unset($_SESSION['nombre']);
        unset($_SESSION['nombreUsuario']);
        unset($_SESSION['email']);
        unset($_SESSION['password']);
        unset($_SESSION['imgPerfil']);
        unset($_SESSION['codPostal']);
        unset($_SESSION['direccion']);
        unset($_SESSION['esAdmin']);
        session_destroy();
    }
}