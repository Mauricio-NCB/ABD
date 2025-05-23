<?php
namespace abd;

use abd\Alquiler as Alquiler;

class FormularioDarComentario extends Formulario
{
    private $idPelicula;

    public function __construct($idPelicula)
    {
        parent::__construct('formDarComentario', ['urlRedireccion' => 'producto.php?id='.$idPelicula]);
        $this->idPelicula = $idPelicula;
    }

    protected function generaCamposFormulario(&$datos)
    {
        $id = $this->idPelicula;
        $comentario = $datos['comentario'] ?? '';
        $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);
        $erroresCampos = self::generaErroresCampos(['comentario'], $this->errores, 'span', array('class' => 'error'));

        $html = <<<EOF
        <input type="hidden" name="id" value="$id">
        <fieldset>
            <legend>Datos para añadir comentario</legend>
            <div>
                <label for="comentario">Comentario: </label>
                <input id="comentario" type="text" name="comentario"/>
                <button type="submit" name="añadirropa">Añadir</button>
                {$erroresCampos['comentario']}
                $htmlErroresGlobales
            </div>
        </fieldset>
        EOF;

        return $html;
    }

    protected function procesaFormulario(&$datos) {
        $this->errores = [];

        if(isset($_SESSION['id'])) {

            $idUsuario = $_SESSION['id'];

            $comentario = trim($datos['comentario'] ?? '');
            $comentario = filter_var($comentario, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if (!$comentario || empty($comentario) ) {
                $this->errores['comentario'] = 'El comentario no puede estar vacío.';
            }

            if (count($this->errores) === 0) {

                if (Alquiler::estaAlquilado($idUsuario, $this->idPelicula)) {
                    
                    $resultado = Alquiler::darComentario($comentario, $idUsuario, $this->idPelicula);
                
                    if(!$resultado){
                        $this->errores[] = "Se ha producido un error inesperado";
                    }
                }
                else {
                    $this->errores[] = 'Necesitas alquilar la pelicula para poder hacer una valoración';
                }

            }
        }
        else {
            $this->errores[] = 'El cliente necesita estar logueado';
        }
    }
}
