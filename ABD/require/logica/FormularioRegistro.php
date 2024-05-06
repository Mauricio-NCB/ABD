<?php
namespace abd;

use abd\Aplicacion as Aplicacion;
use abd\Formulario as Formulario;
use abd\Usuario as Usuario;

class FormularioRegistro extends Formulario {

    public function __construct() {
      parent::__construct('formRegistro', ['enctype' => 'multipart/form-data','urlRedireccion' => 'indice.php']);    
    }
    protected function generaCamposFormulario(&$datos)
    {
        $email = $datos['email'] ?? '';
        $nombreUsuario = $datos['nombreUsuario'] ?? '';
        $nombre = $datos['nombre'] ?? '';
        // Se generan los mensajes de error si existen.
        $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);
        $erroresCampos = self::generaErroresCampos([NULL, 'email', 'nombreUsuario','password', 'password2', 0],
                                                    $this->errores, 'span', array('class' => 'error'));

        $html = <<<EOF
        $htmlErroresGlobales
        <fieldset>
            <legend>Datos para el registro</legend>
            <div>
                <label for="email">Correo electrónico</label>
                <input id="email" type="text" name="email" value="$email" />
                {$erroresCampos['email']}
            </div>
            <div>
                <label for="nombreUsuario">Nombre de usuario:</label>
                <input id="nombreUsuario" type="text" name="nombreUsuario" value="$nombreUsuario" />
                {$erroresCampos['nombreUsuario']}
            </div>
            <div>
                <label for="password">Password:</label>
                <input id="password" type="password" name="password" />
                {$erroresCampos['password']}
            </div>
            <div>
                <label for="password2">Reintroduce el password:</label>
                <input id="password2" type="password" name="password2" />
                {$erroresCampos['password2']}
            </div>                                                                    
            <div>
                <button type="submit" name="registro">Registrar</button>
            </div>
        </fieldset>
        EOF;
        return $html;
    }
    

    protected function procesaFormulario(&$datos)
    {
        $this->errores = [];

        $email = trim($datos['email'] ?? '');
        $email = filter_var($email, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if ( ! $email || empty($email) ) {
            $this->errores['email'] = 'El correo electrónico no puede estar vacío.';
        }

        $nombreUsuario = trim($datos['nombreUsuario'] ?? '');
        $nombreUsuario = filter_var($nombreUsuario, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if ( ! $nombreUsuario || mb_strlen($nombreUsuario) < 5) {
            $this->errores['nombreUsuario'] = 'El nombre de usuario tiene que tener una longitud de al menos 5 caracteres.';
        }

        $password = trim($datos['password'] ?? '');
        $password = filter_var($password, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if ( ! $password || mb_strlen($password) < 5 ) {
            $this->errores['password'] = 'El password tiene que tener una longitud de al menos 5 caracteres.';
        }

        $password2 = trim($datos['password2'] ?? '');
        $password2 = filter_var($password2, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if ( ! $password2 || $password != $password2 ) {
            $this->errores['password2'] = 'Los passwords deben coincidir.';
        }

        //var_dump($_FILES['fotoperf']['error']);
        //var_dump($_FILES['fotoperf']['name']);
        //var_dump(count($_FILES));
        /*$imagen = $_FILES['fotoperf']['error'] == UPLOAD_ERR_OK && count($_FILES) == 1;
        if (!$imagen) {
            $this->errores['fotoperf'] = 'Error al subir el archivo';
            return;
        } 
        $ok = $this->check_file_uploaded_name($_FILES['fotoperf']['name']);        
        if (!$ok){
            $this->errores['fotoperf'] = 'El nombre de la imagen no es valido';
            return;
        }
        if ($this->check_file_uploaded_length($_FILES['fotoperf']['name'])){
            $this->errores['fotoperf'] = 'La longitud de la imagen no es adecuada';
        }
        $destination = __DIR__.'imagenes/FotoPerfiles/'.$_FILES['fotoperf']['name'];
        $extension = pathinfo($_FILES['fotoperf']['name'], PATHINFO_EXTENSION);
        $ok = in_array($extension, self::EXTENSIONES_PERMITIDAS);       
        if (!$ok){
            $this->errores['fotoperf'] = 'Esa extension no esta permitida, por favor suba otro archivo';
            return;
        }
        if (is_file($destination)){
            $this->errores['fotoperf'] = "Ya existe la foto de perfil, prueba con otra";
            unlink(ini_get('upload_tm_dir').$_FILES['fotoperf']['tmp_name']);
            return;
        }
        if (!move_uploaded_file($_FILES['fotoperf'], $destination)){
            $this->errores['fotoperf'] = "No se ha podido mover la imagen a la carpeta de de destino";
            unlink(ini_get('upload_tm_dir').$_FILES['fotoperf']['tmp_name']);
            return;
        }*/
        /*$ok = $_FILES['fotoperf']['error'] == UPLOAD_ERR_OK && count($_FILES) == 1;
        if (! $ok ) {
            $this->errores['fotoperf'] = 'Error al subir el archivo';
            return;
        }  
        $filename = $_FILES['fotoperf']['name'];
       
        $ok = self::check_file_uploaded_name($nombre) && $this->check_file_uploaded_length($nombre);
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        $ok = $ok && in_array($extension, self::EXTENSIONES_PERMITIDAS);
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($_FILES['fotoperf']['tmp_name']);
        $ok = preg_match('/image\/*./', $mimeType);
        if (!$ok) {
            $this->errores['fotoperf'] = 'El archivo tiene un nombre o tipo no soportado';
        }
        if (count($this->errores) > 0) {
            return;
        }
        $tmp_name = $_FILES['fotoperf']['tmp_name'];
        $nombre_file = $filename[strlen($filename)-4];
        var_dump($nombre_file);
        $fichero  = "{$filename}.{$extension}";
        $ruta = implode(DIRECTORY_SEPARATOR, [RUTA_FOTOS_PERFILES]);
        if (!move_uploaded_file($tmp_name, $ruta)) {
            $this->errores['fotoperf'] = 'Error al mover el archivo';
        }
        */

        if (count($this->errores) === 0) {

            $usuario = Usuario::busca($nombreUsuario);
            if($usuario){
                $this->errores[] = "El usuario ya existe";
            }
            else{
                $usuario = Usuario::registra($email,$nombreUsuario,$password,Usuario::USER_ROLE);
                $_SESSION['esAdmin'] = false;
                $_SESSION['login']=true;
                $_SESSION['id'] = $usuario->getId();
                $_SESSION['email'] = $usuario->getEmail();
                $_SESSION['nombreusuario'] = $usuario->getNombreUsuario();
            }
        }
    }

    //Este código pertenece al bitbucket que nos ha aportado el profesor de la asignatura Javier Agapito.
    //Los diferentes creadores de los scripts utilizados están reconocidos encima de sus correspondientes scripts
    /**
     * Check $_FILES[][name]
     *
     * @param (string) $filename - Uploaded file name.
     * @author Yousef Ismaeil Cliprz
     * @See http://php.net/manual/es/function.move-uploaded-file.php#111412
     */
    private static function check_file_uploaded_name($filename)
    {
        return (bool) ((mb_ereg_match('/^[0-9A-Z-_\.]+$/i', $filename) === 1) ? true : false);
    }

    /**
     * Sanitize $_FILES[][name]. Remove anything which isn't a word, whitespace, number
     * or any of the following caracters -_~,;[]().
     *
     * If you don't need to handle multi-byte characters you can use preg_replace
     * rather than mb_ereg_replace.
     * 
     * @param (string) $filename - Uploaded file name.
     * @author Sean Vieira
     * @see http://stackoverflow.com/a/2021729
     */
    private static function sanitize_file_uploaded_name($filename)
    {
        /* Remove anything which isn't a word, whitespace, number
     * or any of the following caracters -_~,;[]().
     * If you don't need to handle multi-byte characters
     * you can use preg_replace rather than mb_ereg_replace
     * Thanks @Łukasz Rysiak!
     */
        $newName = mb_ereg_replace("([^\w\s\d\-_~,;\[\]\(\).])", '', $filename);
        // Remove any runs of periods (thanks falstro!)
        $newName = mb_ereg_replace("([\.]{2,})", '', $newName);

        return $newName;
    }

    /**
     * Check $_FILES[][name] length.
     *
     * @param (string) $filename - Uploaded file name.
     * @author Yousef Ismaeil Cliprz.
     * @See http://php.net/manual/es/function.move-uploaded-file.php#111412
     */
    private function check_file_uploaded_length($filename)
    {
        return (bool) ((mb_strlen($filename, 'UTF-8') < 250) ? true : false);
    }
    
}

?>