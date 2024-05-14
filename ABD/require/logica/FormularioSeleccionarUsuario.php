<?php
namespace abd;

use abd\Formulario as Formulario;
use abd\Usuario as Usuario;

class FormularioSeleccionarUsuario extends Formulario {

    //string con la pagina a la que nos llevará al completarse el formulario de sel
    private $paginaRedireccion;

    public function __construct($paginaRedireccion) {
        parent::__construct('formSelUsuario');

        $this->paginaRedireccion = $paginaRedireccion;
    }

    protected function generaCamposFormulario(&$datos) {
        $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);
        $erroresCampos = self::generaErroresCampos([], $this->errores, 'span', array('class' => 'error'));

        //muestra todos los usuarios
        $usuarios = Usuario::listaUsuarios();
        
        //bucle de opciones con cada usuario
        $options = '';

        foreach($usuarios as $usuario) {
            if ($usuario->getNombreUsuario() != $_SESSION['nombreusuario']) {
                $options .= '<option value="' .$usuario->getNombreUsuario(). '">' .$usuario->getNombreUsuario(). '</option>';
            }
        }

        $html = <<<EOF
        $htmlErroresGlobales
        <fieldset>
            <div>
                <label for="idUsuario"> Elige el usuario deseado: </label>
                <select id="idUsuario" name="idUsuario">
                $options
                </select>
            </div>
            <div>
                <button type="submit" name="Buscar">Buscar</button>
            </div>
        </fieldset>
        EOF;

        return $html;
    }

    protected function procesaFormulario(&$datos) {
        $usuarioEncontrado = Usuario::busca($_REQUEST["idUsuario"]);

        if ($this->paginaRedireccion == 'usuarioElegido') {
            $nombreUsuario = $usuarioEncontrado->getNombreUsuario();
            header("Location: $this->paginaRedireccion.php?nombreusuario=$nombreUsuario");
        }

        else if ($this->paginaRedireccion == 'alquilerElegido') {
            $idUsuario = $usuarioEncontrado->getId();
            header("Location: $this->paginaRedireccion.php?idUsuario=$idUsuario");
        }


    }
}