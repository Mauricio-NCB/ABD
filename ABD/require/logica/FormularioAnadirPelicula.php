<?php
namespace abd;

use abd\Aplicacion as Aplicacion;
use abd\Formulario as Formulario;
use abd\Pelicula as Pelicula;

class FormularioAnadirPelicula extends Formulario {

    public function __construct() {
        parent::__construct('formAnadirPelicula', ['urlRedireccion'=>'administracion.php']);
    }

    protected function generaCamposFormulario(&$datos){

        $nombre = $datos['nombre'] ?? '';
        $descripcion = $datos['descripcion'] ?? '';
        $precio = $datos['precio'] ?? '';

        $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);
        $erroresCampos = self::generaErroresCampos(['nombre', 'descripcion', 'precio'],
                                                    $this->errores, 'span', array('class' => 'error'));
        

        $html = <<<EOF
        $htmlErroresGlobales
        <fieldset>
            <legend>Datos para añadir una película</legend>
            <div>
                <label for="nombre">Nombre de la película</label>
                <input id="nombre" type="text" name="nombre" value="$nombre" />
                {$erroresCampos['nombre']}
            </div>
            <div>
                <label for="descripcion">Descripcion de la película</label>
                <input id="descripcion" type="text" name="descripcion" value="$descripcion" />
                {$erroresCampos['descripcion']}
            </div>
            <div>
                <label for="precio">Precio de la película</label>
                <input id="precio" type="text" name="precio" value="$precio" />
                {$erroresCampos['precio']}
            </div>
            <button type="submit" name="añadirPelicula">Añadir</button>
        </fieldset>
        EOF;
        
        return $html;
    }
    protected function procesaFormulario(&$datos)
    {
        $this->errores = [];

        $nombre = trim($datos['nombre'] ?? '');
        $nombre = filter_var($nombre, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if ( ! $nombre || empty($nombre) ) {
            $this->errores['nombre'] = 'El nombre no puede estar vacío.';
        }

        $descripcion = trim($datos['descripcion'] ?? '');
        $descripcion = filter_var($descripcion, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if ( ! $descripcion || empty($descripcion)) {
            $this->errores['descripcion'] = 'La descripcion no puede estar vacía.';
        }

        $precio = trim($datos['precio'] ?? '');
        $precio = filter_var($precio, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if ( ! $precio || empty($precio) ) {
            $this->errores['precio'] = 'El precio no puede estar vacío.';
        }

        if (count($this->errores) === 0) {
            
            if(Pelicula::mismaPelicula($nombre)){
                $this->errores[] = "No se permiten dos películas con el mismo título";
            }
            else{
                if (!Pelicula::anadirPelicula($nombre, $descripcion, $precio,)){
                    $this->errores[] = "La prenda no ha podido ser insertada en la base de datos";
                }
            }
                
        }
    }
}

?>