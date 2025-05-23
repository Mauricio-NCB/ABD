<?php
namespace abd;

use abd\Aplicacion as Aplicacion;
use abd\Formulario as Formulario;
use abd\Usuario as Usuario;

class FormularioAñadirTarjeta extends Formulario {

    public function __construct() {
      parent::__construct('formRegistro', ['enctype' => 'multipart/form-data','urlRedireccion' => 'indice.php']);    
    }
    protected function generaCamposFormulario(&$datos)
    {
        $idUsuario = $datos['idUsuario'] ?? '';
        $numtarjeta = $datos['numtarjeta'] ?? '';
        $fechatarjeta = $datos['fechatarjeta'] ?? '';
        $cvvtarjeta = $datos['cvvtarjeta'] ?? '';
        // Se generan los mensajes de error si existen.
        $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);
        $erroresCampos = self::generaErroresCampos([NULL, 'idUsuario','numtarjeta', 'fechatarjeta', 'cvvtarjeta'],
                                                    $this->errores, 'span', array('class' => 'error'));

        $html = <<<EOF
        $htmlErroresGlobales
        <fieldset>
            <div>
                <label for="numtarjeta">Numero Tarjeta Bancaria:</label>
                <input id="numtarjeta" type="text" name="numtarjeta" value="$numtarjeta" />
                {$erroresCampos['numtarjeta']}
            </div>
            <div>
                <label for="fechatarjeta">Fecha Caducidad Tarjeta:</label>
                <input id="fechatarjeta" type="date" name="fechatarjeta" value="$fechatarjeta" />
                {$erroresCampos['fechatarjeta']}
            </div>
            <div>
                <label for="cvvtarjeta">CVV Tarjeta:</label>
                <input id="cvvtarjeta" type="text" name="cvvtarjeta" value="$cvvtarjeta" />
                {$erroresCampos['cvvtarjeta']}
            </div>                                                                       
            <div>
                <button type="submit" name="registro">Registrar</button>
            </div>
        </fieldset>
        EOF;
        return $html;
    }
    

    protected function procesaFormulario(&$datos)
    {
        $this->errores = [];

        $numtarjeta = trim($datos['numtarjeta'] ?? '');
        $numtarjeta = filter_var($numtarjeta, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $numtarlong = strlen((string)$numtarjeta);

        if ( ! $numtarjeta || empty($numtarjeta) ) {
            $this->errores['numtarjeta'] = 'El numero de tarjeta no puede estar vacío y tiene que ser 16 números.';
        }
        else if(!ctype_digit($numtarjeta)){
            $this->errores['numtarjeta'] = 'El número de la tarjeta tiene que ser numérico';
        }
        else if($numtarlong != 16){
            $this->errores['numtarjeta'] = 'El numero de la tarjeta tiene que tener 16 números.';
        }

        $fechatarjeta = trim($datos['fechatarjeta'] ?? '');
        $fechatarjeta = filter_var($fechatarjeta, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if ( ! $fechatarjeta || empty($fechatarjeta) ) {
            $this->errores['fechatarjeta'] = 'La fecha de la tarjeta no puede estar vacío.';
        }

        $cvvtarjeta = trim($datos['cvvtarjeta'] ?? '');
        $cvvtarjeta = filter_var($cvvtarjeta, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $cvvtarlong = strlen((string)$cvvtarjeta);
        if ( ! $cvvtarjeta || empty($cvvtarjeta) ) {
            $this->errores['cvvtarjeta'] = 'El cvv no puede estar vacía y tener 3 números.';
        }
        else if(!is_numeric($cvvtarjeta)){
            $this->errores['cvvtarjeta'] = 'El cvv tiene que ser numérico';
        }
        else if($cvvtarlong != 3){
            $this->errores['cvvtarjeta'] = 'El cvv tiene que tener 3 números.';
        }

        if (count($this->errores) === 0) {
            $usuario = Usuario::buscaPorCorreo($_SESSION['email']);
            if(!$usuario){
                $this->errores[] = "No se puede añadir un metodo de pago a un usuario no existente";
            }
            else if(Usuario::buscaTarjeta($numtarjeta,$usuario->getId())){
                $this->errores[] = "El usuario ya tiene esa tarjeta registrada";
            }
            else{
                $usuario = Usuario::registraTarjeta($numtarjeta,$fechatarjeta,$cvvtarjeta);
            }
        }
    }
}

?>