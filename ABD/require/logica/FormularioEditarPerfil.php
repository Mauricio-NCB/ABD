<?php
namespace abd;

use abd\Aplicacion as Aplicacion;
use abd\Formulario as Formulario;
use abd\Usuario as Usuario;

class FormularioEditarPerfil extends Formulario {

    public function __construct() {
        parent::__construct('formEditarPerfil', ['urlRedireccion' => 'indice.php']);
    }
    
    protected function generaCamposFormulario(&$datos)
    {
        //Datos de la sesion

        $correo = $_SESSION['email'];
        $user = $_SESSION['nombreusuario'];
        $pass = $_SESSION['password'];
        $esAdmin =  $_SESSION['esAdmin'];

        // Se generan los mensajes de error si existen.
        $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);
        $erroresCampos = self::generaErroresCampos(['nombreUsuario', 'correo',  'password','esAdmin'], 
                                                    $this->errores, 'span', array('class' => 'error'));

        // Se genera el HTML asociado a los campos del formulario y los mensajes de error.
        $html = <<<EOF
        $htmlErroresGlobales
        <fieldset>
            <div>
            <p> <label for="correo"> Correo </label>
                <input id='correo' type='text' name='correo'  placeholder='$correo' readonly/> </p>
                {$erroresCampos['correo']}
            </div>

            <div>
            <p>
                <label for="nombreUsuario"> Nombre Usuario </label>
                <input id="nombreUsuario" type="text" name="nombreUsuario"  placeholder='$user'/> <p>
                {$erroresCampos['nombreUsuario']}
            </div>

            <div>
            <p>
                <label for="password"> Password Nueva </label>
                <input id="password_nueva" type="password" name="password_nueva"  placeholder="********" required /> </p>
                {$erroresCampos['password']}
            </div> 

            <div>
            <p>
                <label for="esAdmin"> Rol (1 Admin, 0 Usuario) </label>
                <input id="esAdmin" type="text" name="esAdmin"  placeholder='$esAdmin' readonly/> </p>
                {$erroresCampos['esAdmin']}
            </div>
            <div>
                <button type="submit" name="editar">Editar</button>
            </div>
        </fieldset>
        EOF;
        return $html;
    }

    /*
     
        */


    protected function procesaFormulario(&$datos)
    {
        $this->errores = [];
        
        $nombreUsuario = trim($datos['nombreUsuario'] ?? '');
        $nombreUsuario = filter_var($nombreUsuario, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        if (!$nombreUsuario || empty($nombreUsuario)) {
            $nombreUsuario = $_SESSION['nombreusuario'];
        }
      
        $password_nueva = trim($datos['password_nueva'] ?? '');
       

        $esAdmin = trim($datos['esAdmin'] ?? '');
        $esAdmin = filter_var($esAdmin, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        if (!$esAdmin || empty($esAdmin) ) {
           $esAdmin = $_SESSION['esAdmin'];
        }

        /*
        if (!is_uploaded_file($datos['imgPerfil']['tmp-name'])){
            $this->errores['imgPerfil'] = "El fichero de imagen no se ha subido correctamente";
        }
        $imgPerfil = $datos['imgPerfil']['tmp_name'];
        $destination = __DIR__.'imagenes/'.$datos['imgPerfil']['nombre'];
        if (is_file($destination)){
            $this->errores['imgPerfil'] = "Ya existe la foto de perfil, prueba con otra";
            unlink(ini_get('upload_tm_dir').$datos['imgPerfil']['tmp_name']);
            exit;
        }
        if (!move_uploaded_file($imgPerfil, $destination)){
            $this->errores['imgPerfil'] = "No se ha podido mover la imagen a la carpeta de de destino";
            unlink(ini_get('upload_tm_dir').$datos['imgPerfil']['tmp_name']);
            exit;
        }
        */

        
        if (count($this->errores) === 0) {
            $usuario = Usuario::editarDatos($_SESSION['email'], $nombreUsuario, $password_nueva,$esAdmin);
        
            if (!$usuario) {
                $this->errores[] = "Error al editar los datos";
            } else {
                $_SESSION['email'] = $correo;
                $_SESSION['nombreusuario'] = $nombreUsuario;
                $_SESSION['esAdmin'] = $esAdmin;
                $_SESSION['password'] = $password_nueva; 
                //$_SESSION['imgPerfil'] = $imgPerfil; 
            }
        }
    }
}