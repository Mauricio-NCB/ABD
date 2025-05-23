<?php
namespace abd;

use abd\Aplicacion as Aplicacion;
use abd\Formulario as Formulario;
use abd\Pelicula as Pelicula;

class FormularioEliminarPelicula extends Formulario{

    public function __construct(){
        parent::__construct('formEliminarPelicula', ['urlRedireccion'=>'administracion.php']);
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
            <legend>Elige la película que quieras eliminar: </legend>
            <div>
                <label for="nombre">Nombre de la película que quieres eliminar</label>
                <select id="nombre" name="nombre">
                $options
                </select>
            </div>
            <div>
                <button type="submit" name="eliminar">Eliminar</button>
            </div>
        </fieldset>
        EOF;

        return $html;
    }
    protected function procesaFormulario(&$datos) {

        Pelicula::eliminarPelicula($datos['nombre']);
    }
}