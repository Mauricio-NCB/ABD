<?php
namespace abd;

use abd\Aplicacion as Aplicacion;
use abd\Formulario as Formulario;
use abd\Usuario as Usuario;

class FormularioModificarPelicula extends Formulario {

    public function __construct() {
        parent::__construct('formModificarPelicula', ['urlRedireccion' => 'indice.php']);
    }
    
    protected function generaCamposFormulario(&$datos)
    {
        //Datos de la sesion

        $pelicula = Pelicula::busca($_REQUEST['nombrepelicula']);

        $nombre = $pelicula->getNombre();
        $descripcion = $pelicula->getDescripcion();
        $precio = $pelicula->getPrecio();
        $valoracion = $pelicula->getValoracion();
        $comentarios = $pelicula->getComentarios();

        // Se generan los mensajes de error si existen.
        $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);
        $erroresCampos = self::generaErroresCampos(['nombre', 'descripcion',  'precio'], 
                                                    $this->errores, 'span', array('class' => 'error'));

        // Se genera el HTML asociado a los campos del formulario y los mensajes de error.
        $html = <<<EOF
        $htmlErroresGlobales
        <fieldset>
            <div>
            <p> <label for="nombre"> Nombre nuevo</label>
                <input id='nombre' type='text' name='nombre'  placeholder='$nombre'/> </p>
                {$erroresCampos['nombre']}
            </div>

            <div>
            <p>
                <label for="descripcion"> Descripción nueva </label>
                <input id="descripcion" type="text" name="descripcion"  placeholder='$descripcion'/> <p>
                {$erroresCampos['descripcion']}
            </div>

            <div>
            <p>
                <label for="precio"> Precio nuevo </label>
                <input id="precio" type="precio" name="precio"  placeholder="$precio"/> </p>
                {$erroresCampos['precio']}
            </div> 

            <div>
            <div>
                <button type="submit" name="editar">Editar</button>
            </div>
        </fieldset>
        EOF;
        return $html;
    }

    protected function procesaFormulario(&$datos)
    {
        $this->errores = [];
        
        $nombre = trim($datos['nombre'] ?? '');
        $nombre = filter_var($nombre, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        if (!$nombre || empty($nombre)) {
            $this->errores['nombre'] = 'El nombre no puede estar vacío';
        }
      
        $descripcion = trim($datos['descripcion'] ?? '');
        $descripcion = filter_var($descripcion, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        if (!$descripcion || empty($descripcion)) {
            $this->errores['descripcion'] = 'La descripción no puede estar vacío';
        }

        $precio = trim($datos['precio'] ?? '');
        $precio = filter_var($precio, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        if (!$precio || empty($precio)) {
            $this->errores['precio'] = 'El precio no puede estar vacío';
        }

        if (count($this->errores) === 0) {
            //Falta esto mas la correccion

            $pelicula = Pelicula::editarPelicula($_SESSION['email'], $nombreUsuario, $password_nueva,$esAdmin);
        
            if (!$usuario) {
                $this->errores[] = "Error al editar los datos";
            } else {
                $_SESSION['email'] = $correo;
                $_SESSION['nombreusuario'] = $nombreUsuario;
                $_SESSION['esAdmin'] = $esAdmin;
                $_SESSION['password'] = $password_nueva; 
            }
        }
    }
}