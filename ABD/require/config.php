<?php
use abd\Aplicacion;

spl_autoload_register(function ($class){    
    $prefix ='abd\\';

    $length = strlen($prefix);
    $base_directory= __DIR__.'/logica/';

    if (strncmp($prefix, $class, $length) !== 0){
        return;
    }
    $relative_class = substr($class, $length);
    $file = $base_directory.str_replace('\\', '/', $relative_class).'.php';

    if (file_exists($file)){
        require $file;
    }
});
//TODO Cambiar puerto y usarlo mas adelante
const PORT = 300;
//Parámetros de configuración de la Aplicación
define('RAIZ_APP', __DIR__);
define('RUTA_APP', '');
define('RUTA_IMGS', RUTA_APP.'/imagenes');
define('RUTA_CSS', RUTA_APP.'/css');
define('RUTA_JS', RUTA_APP.'/js');
define('RUTA_FOTOS_PERFILES', implode(DIRECTORY_SEPARATOR, [dirname(__DIR__), 'imagenes\FotosPerfiles']));
//Parámetros de configuración de la BD
define('BD_HOST', 'localhost');
define('BD_NAME', 'abd');
define('BD_USER', 'admin');
define('BD_PASS', 'adminpass');

//const SERVERNAME = 'localhost';
//const user = '';
//const PASS = '':
//const DB= 'LibertyClothing';
//const APP_DIR = __DIR__ . DIRECTORY_SEPARATOR;
//const LOCAL = SERVERNAME . ":" . PORT . DIRECTORY_SEPARATOR;

$app = Aplicacion::getInstance();
$app->init(['host'=>BD_HOST, 'bd'=>BD_NAME, 'user'=>BD_USER, 'pass'=>BD_PASS]);


?>