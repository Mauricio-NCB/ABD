<?php
namespace abd;

use abd\Formulario as Formulario;
use abd\Pelicula as Pelicula;

class FormularioSeleccionarPelicula extends Formulario {

    public function __construct() {
        parent::__construct('formSelPelicula');
    }

    protected function generaCamposFormulario(&$datos){

        $nombre = $datos['nombre'] ?? '';

        $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);
        $erroresCampos = self::generaErroresCampos(['nombre'],
                                                    $this->errores, 'span', array('class' => 'error'));
        
        $peliculas = Pelicula::mostrarCatalogo();

        $options = '';

        foreach($peliculas as $pelicula) {
            $options .= '<option value="' .$pelicula->getNombre(). '">' .$pelicula->getNombre(). '</option>';
        }

        $html = <<<EOF
        $htmlErroresGlobales
        <fieldset>
            <legend>Elige la película que quieres modificar: </legend>
            <div>
                <label for="peliculaElegida">Nombre de la película que quieres modificar</label>
                <select id="peliculaElegida" name="peliculaElegida">
                $options
                </select>
            </div>
            <div>
                <button type="submit" name="modificar">Modificar</button>
            </div>
        </fieldset>
        EOF;

        return $html;
    }

    protected function procesaFormulario(&$datos) {
        $peliculaElegida = Pelicula::busca($datos['peliculaElegida']);
        $nombrePelicula = $peliculaElegida->getNombre();

        header("Location: peliculaElegida.php?nombrepelicula=$nombrePelicula");
    }
}