<?php
namespace abd;

use abd\Alquiler as Alquiler;

class FormularioDarPuntuacion extends Formulario
{
    private $idPelicula;

    public function __construct($idPelicula)
    {
        parent::__construct('formDarPuntuacion', ['urlRedireccion' => 'producto.php?id='.$idPelicula]);
        $this->idPelicula = $idPelicula;
    }

    protected function generaCamposFormulario(&$datos)
    {
        $id = $this->idPelicula;
        $puntuacion = $datos['puntuacion'] ?? '';
        $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);
        $erroresCampos = self::generaErroresCampos(['puntuacion'], $this->errores, 'span', array('class' => 'error'));

        $options = '';

        for ($i = 1; $i <= 10; $i++) {
            $options .= '<option value="' .$i. '">' .$i. '</option>';
        }

        $html = <<<EOF
        <input type="hidden" name="id" value="$id">
        <fieldset>
            <legend>Datos para añadir puntuacion</legend>
            <div>
                <label for="puntuacion">Tu puntuacion: </label>
                <select id="puntuacion" name="puntuacion">
                $options
                </select>
                <button type="submit" name="Valorar">Valorar</button>
                {$erroresCampos['puntuacion']}
                $htmlErroresGlobales
            </div>
        </fieldset>
        EOF;

        return $html;
    }

    protected function procesaFormulario(&$datos) {

        $idUsuario = $_SESSION['id'];

        if (Alquiler::estaAlquilado($idUsuario, $this->idPelicula)) {
            
            $resultado = Alquiler::darPuntuacion($puntuacion, idUsuario, $this->idPelicula);
        
            if(!$resultado){
                $this->errores[] = "Se ha producido un error inesperado";
            }
        }
        else {
            $this->errores[] = 'Necesitas alquilar la pelicula para poder hacer una valoración';
        }

    }
}