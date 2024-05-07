<?php
namespace abd;

class FormularioDarComentario extends Formulario
{
    private $idPelicula;

    public function __construct($formDarComentario, $idPelicula, $opciones = array())
    {
        parent::__construct($formDarComentario, $opciones);
        $this->idPelicula = $idPelicula;
    }

    protected function generaCamposFormulario(&$datos)
    {
        // Llama al método de la clase padre para obtener los campos del formulario base
        $camposBase = parent::generaCamposFormulario($datos);
        
        // Agrega un campo oculto para la ID de la película
        $idPeliculaInput = '<input type="hidden" name="idPelicula" value="' . htmlspecialchars($this->idPelicula) . '" />';
        
        // Retorna la concatenación de los campos del formulario base y el campo de la ID de la película
        return $camposBase . $idPeliculaInput;
    }
}
