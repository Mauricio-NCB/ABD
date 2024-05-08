<?php
namespace abd;

use abd\Alquiler as Alquiler;
use abd\Usuario as Usuario;

class FormularioAlquilar extends Formulario
{
    private $idPelicula;
    private $precio;

    public function __construct($idPelicula, $precio)
    {
        parent::__construct('formAlquilar', ['urlRedireccion' => 'producto.php?id='.$idPelicula]);
        $this->idPelicula = $idPelicula;
        $this->precio = $precio;
    }

    protected function generaCamposFormulario(&$datos)
    {
        $id = $this->idPelicula;
        $precio = $this->precio;
        $alquiler = $datos['alquiler'] ?? '';
        $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);
        $erroresCampos = self::generaErroresCampos(['alquiler'], $this->errores, 'span', array('class' => 'error'));

        $html = <<<EOF
        <input type="hidden" name="id" value="$id">
        <fieldset>
            <legend>Precio</legend>
            <div>
                <label for="alquiler">Ahora en tu pantalla por $precio €</label>
                <button type="submit" class="btn btn-primary">Alquilar</button>
                {$erroresCampos['alquiler']}
                $htmlErroresGlobales
            </div>
        </fieldset>
        EOF;

        return $html;
    }

    protected function procesaFormulario(&$datos) {

        $idUsuario = $_SESSION['id'];

        if(!Usuario::tieneTarjeta($idUsuario)) {
            $this->errores[] = "El usuario necesita registrar una tarjeta para alquilar";
        }
        else {
            $fechaActual = date("Y-m-d"); // Formato adecuado para strtotime()
            $fechaDestino = date("Y-m-d", strtotime("+1 month", strtotime($fechaActual)));
    
            Alquiler::nuevoAlquiler($idUsuario, $this->idPelicula, $fechaActual, $fechaDestino);
        }
    }
}