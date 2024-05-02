<?php
require_once __DIR__.'/require/config.php';

use abd\Aplicacion as Aplicacion;
use abd\FormularioEditarPerfil as FormularioEditarPerfil;
use abd\Usuario as Usuario;

$tituloPagina = 'Mi perfil';

$usuario = Usuario::buscaporCorreo($_SESSION['email']);
$formEdit = new FormularioEditarPerfil();
$htmlForm = $formEdit->gestiona();

$contenidoPrincipal=<<<EOS
<h1>Datos de mi perfil</h1>  
 

<div class="contenedor">
     $htmlForm   
</div>

EOS;
$userID = $usuario->getId();

$app = Aplicacion::getInstance();
$conn = $app->getConexionBd();
if ($conn->connect_error) {
     die("La conexión ha fallado" . $conn->connect_error);
}
$query = "SELECT * FROM tarjeta WHERE idUsuario = '$userID'";
$result = $conn->query($query);

if($result->num_rows!=0){
     while($row = $result->fetch_array()){
          $numeroTarjeta = $row['numeroTarjeta'];
          $contenidoPrincipal .= <<< EOS
          <p>Numero de tarjeta: $numeroTarjeta
          <form action="borrarTarjeta.php" method="post" style="display: inline;">
          <p> Deseas borrar esta tarjeta?:
               <input type="hidden" name="idUser" value="$userID">
               <input type="hidden" name="numTarjeta" value="$numeroTarjeta">
               <button type="submit">Borrar Tarjeta</button>
          </form>
          <p>------------------------------</p>
          EOS;
     }
     
}
else {
     $contenidoPrincipal .= <<<EOS
     <p>No hay ningúna tarjeta añadida.</p>
     <p>--------------------------</p>
     EOS;
}
 $contenidoPrincipal .= <<<EOS
<a href='tarjeta.php'>Añadir Tarjeta</a><br>
EOS;

require __DIR__.'/require/vistas/plantillas/plantilla.php';


?>