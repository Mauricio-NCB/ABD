<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
    <!-- Inclusión de css a posteriori -->
    <title><?= $tituloPagina ?></title>
</head>
<body>
<div id="contenedor">

<?php require('require/vistas/comun/cabecera.php');?>

<?= $contenidoPrincipal ?>

<?php require('require/vistas/comun/pie.php');?>

</div>
</body>
</html>