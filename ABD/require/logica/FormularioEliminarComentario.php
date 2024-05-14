<?php
namespace abd;

use abd\Alquiler as Alquiler;

class FormularioEliminarComentario extends Formulario
{
    private $idUsuario;
    private $idPelicula;
    protected $urlRedireccion;

    public function __construct($idUsuario, $idPelicula)
    {
        parent::__construct("formDelComentario_{$idPelicula}");
        $this->idUsuario = $idUsuario;
        $this->idPelicula = $idPelicula;
    }

    protected function generaCamposFormulario(&$datos)
    {
        $idPelicula = $this->idPelicula;
        $idUsuario = $this->idUsuario;
        $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);

        $this->urlRedireccion = $_SERVER['PHP_SELF'];
        if (basename($this->urlRedireccion) === 'alquilerElegido.php') {
            $this->urlRedireccion .= "?idUsuario=$idUsuario";
        }  

        $html = <<<EOF
            <input type="hidden" name="idUsuario" value="{$idUsuario}">
            <input type="hidden" name="idPelicula" value="{$idPelicula}">
            <button type="submit">Eliminar Comentario</button>
            $htmlErroresGlobales
        EOF;

        return $html;
    }

    protected function procesaFormulario(&$datos) {

        if (!Alquiler::eliminarComentario($this->idUsuario, $this->idPelicula)) {
            $this->errores[] = "Ha ocurrido un error inesperado";
        }

        $puntuacion = Pelicula::obtenerValoracion($this->idPelicula, $this->idUsuario);
        $comentario = Pelicula::obtenerComentarios($this->idPelicula, $this->idUsuario);

        if (empty($puntuacion) && empty($comentario)) {
            Alquiler::eliminarValoracion($this->idUsuario, $this->idPelicula);
        }

        header("Location: ".$this->urlRedireccion);
    }
}