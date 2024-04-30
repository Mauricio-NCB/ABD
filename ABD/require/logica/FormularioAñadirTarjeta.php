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
                <label for="numtarjeta">Numero Tarejeta Bancaria:</label>
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
        else if(!is_numeric($numtarjeta)){
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

    //Este código pertenece al bitbucket que nos ha aportado el profesor de la asignatura Javier Agapito.
    //Los diferentes creadores de los scripts utilizados están reconocidos encima de sus correspondientes scripts
    /**
     * Check $_FILES[][name]
     *
     * @param (string) $filename - Uploaded file name.
     * @author Yousef Ismaeil Cliprz
     * @See http://php.net/manual/es/function.move-uploaded-file.php#111412
     */
    private static function check_file_uploaded_name($filename)
    {
        return (bool) ((mb_ereg_match('/^[0-9A-Z-_\.]+$/i', $filename) === 1) ? true : false);
    }

    /**
     * Sanitize $_FILES[][name]. Remove anything which isn't a word, whitespace, number
     * or any of the following caracters -_~,;[]().
     *
     * If you don't need to handle multi-byte characters you can use preg_replace
     * rather than mb_ereg_replace.
     * 
     * @param (string) $filename - Uploaded file name.
     * @author Sean Vieira
     * @see http://stackoverflow.com/a/2021729
     */
    private static function sanitize_file_uploaded_name($filename)
    {
        /* Remove anything which isn't a word, whitespace, number
     * or any of the following caracters -_~,;[]().
     * If you don't need to handle multi-byte characters
     * you can use preg_replace rather than mb_ereg_replace
     * Thanks @Łukasz Rysiak!
     */
        $newName = mb_ereg_replace("([^\w\s\d\-_~,;\[\]\(\).])", '', $filename);
        // Remove any runs of periods (thanks falstro!)
        $newName = mb_ereg_replace("([\.]{2,})", '', $newName);

        return $newName;
    }

    /**
     * Check $_FILES[][name] length.
     *
     * @param (string) $filename - Uploaded file name.
     * @author Yousef Ismaeil Cliprz.
     * @See http://php.net/manual/es/function.move-uploaded-file.php#111412
     */
    private function check_file_uploaded_length($filename)
    {
        return (bool) ((mb_strlen($filename, 'UTF-8') < 250) ? true : false);
    }
    
}

?>