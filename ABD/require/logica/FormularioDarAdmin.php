<?php
namespace abd;

use abd\Aplicacion as Aplicacion;
use abd\Formulario as Formulario;
use abd\Usuario as Usuario;

class FormularioDarAdmin extends Formulario {

    public function __construct() {
        parent::__construct('formDarAdmin', ['urlRedireccion' => 'indice.php']);
    }

    protected function generaCamposFormulario(&$datos) {

        $usuario = Usuario::busca($_REQUEST['nombreusuario']);

        $correo = $usuario->getEmail();
        $nombreUsuario = $usuario->getNombreUsuario();

        $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);
        $erroresCampos = self::generaErroresCampos([], $this->errores, 'span', array('class' => 'error'));

        if ($usuario->getRol() == 1) {
            $botonAdmin = '<label for="admin">Este usuario ya es administrador</label>';
        }
        else {
            $botonAdmin = '<div> <button type="submit" name="Guardar">Dar permiso de administrador</button> </div>';
        }

        $html = <<<EOF
        $htmlErroresGlobales
        <fieldset>
            <div>
            <p> <label for="correo"> Correo </label>
                <input id='correo' type='text' name='correo' value='$correo' readonly/> </p>
            </div>

            <div>
            <p>
                <label for="nombreUsuario"> Nombre Usuario </label>
                <input id="nombreUsuario" type="text" name="nombreUsuario" value='$nombreUsuario' readonly/> <p>
            </div>

            <div>
            <p>
                <label for="password"> Password </label>
                <input id="password" type="password" name="password"  value="********" readonly/> </p>
            </div> 

            $botonAdmin

        </fieldset>
        EOF;
        return $html;
    }

    protected function procesaFormulario(&$datos) {

        if (count($this->errores) === 0) {
            $result = Usuario::darAdmin($datos['correo']);
        }
    }
}