<?php
namespace abd;

use abd\Alquiler as Alquiler;

class FormularioEliminarPuntuacion extends Formulario
{
    private $idUsuario;
    private $idPelicula;

    public function __construct($idUsuario, $idPelicula)
    {
        parent::__construct('formDelPuntuacion', ['urlRedireccion' => 'gestionAlquileres.php']);
        $this->idUsuario = $idUsuario;
        $this->idPelicula = $idPelicula;
    }

    protected function generaCamposFormulario(&$datos)
    {
        $idPelicula = $this->idPelicula;
        $idUsuario = $this->idUsuario;
        $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);

        $html = <<<EOF
            <input type="hidden" name="idUsuario" value="{$idUsuario}">
            <input type="hidden" name="idPelicula" value="{$idPelicula}">
            <button type="submit">Eliminar Puntuación</button>
            $htmlErroresGlobales
        EOF;

        return $html;
    }

    protected function procesaFormulario(&$datos) {

        if (!Alquiler::eliminarPuntuacion($this->idUsuario, $this->idPelicula)) {
            $this->errores[] = "Ha ocurrido un error inesperado";
        }

        if (!Alquiler::estaPuntuado($this->idUsuario, $this->idPelicula) &&
            !Alquiler::estaComentado($this->idUsuario, $this->idPelicula)) {
                Alquiler::eliminarValoracion($this->idUsuario, $this->idPelicula);
        }
    }
}