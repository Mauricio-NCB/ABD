<?php
namespace abd;

use abd\Formulario as Formulario;
use abd\Pelicula as Pelicula;

class FormularioModificarPelicula extends Formulario {

    public function __construct() {
        parent::__construct('formModPelicula', ['urlredireccion'=>'administracion.php']);
    }

    protected function generaCamposFormulario(&$datos) {
        $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);
        $erroresCampos = self::generaErroresCampos([], $this->errores, 'span', array('class' => 'error'));

        //muestra todos los usuarios
        $usuarios = Usuario::listaUsuarios();
        
        //bucle de opciones con cada usuario
        $options = '';

        foreach($usuarios as $usuario) {
            $options .= '<option value="' .$usuario->getNombreUsuario(). '">' .$usuario->getNombreUsuario(). '</option>';
        }

        $html = <<<EOF
        $htmlErroresGlobales
        <fieldset>
            <div>
                <label for="usuarioElegido"> Elige el usuario que quieres modificar: </label>
                <select id="usuarioElegido" name="usuarioElegido">
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
        $usuarioElegido = Usuario::busca($datos['usuarioElegido']);
        $nombreUsuario = $usuarioElegido->getNombreUsuario();

        header("Location: usuarioElegido.php?nombreusuario=$nombreUsuario");
    }
}