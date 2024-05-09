<header>
    <h1>Pagina de alquiler de peliculas</h1>

    <?php 

        use abd\FormularioLogout as FormularioLogout;

        if(isset($_SESSION["login"]) && $_SESSION["login"]) {

            $formLogout = new FormularioLogout();
            $htmlForm = $formLogout->gestiona();

            echo "<p> Bienvenido {$_SESSION['nombreusuario']} $htmlForm";

            echo("
                <form action='editaPerfil.php'>
                    <button type='submit'>Editar perfil</button>
                </form>
            ");
            echo("
            <form action='verPelisAlquiladas.php'>
                <button type='submit'>Pelis Alquiladas</button>
            </form>
        ");

            if (isset($_SESSION['esAdmin']) && $_SESSION['esAdmin']) {
                echo("
                    <form action='administracion.php'>
                        <button type='submit'>Administración</button>
                    </form>
                ");
            }
        }
        else{
            echo "<p> Usuario Desconocido </p>";
            echo "<p> <a href=login.php>  Login </a>";
            echo "<p> <a href=registro.php>  Registrate </a> </p>";
        }
    ?>
</header>

<?php
    echo "<div class='barranav'>";
    echo "<ul>";
    echo "<li><a href=indice.php>Inicio</a></li>";
    echo "<li><a href=catalogo.php>Catálogo</a></li>";
    echo "<ul>";
    echo "</div>";
?>
