<?php require_once("../conexion.php"); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Directorio KitsuFlux</title>
    <link rel="stylesheet" href="css/directorio.css">
    <link rel="icon" href="../imagenes/kitsuflux.ico" type="image/x-icon">
    <link rel="stylesheet" href="../estilos.css">

</head>
<body>
    
   <?php 
   include("../header/header.php");
   ?>
<div class="seccion-buscador">
        <div class="controles">
            <input type="text" id="cajaBusqueda" placeholder="Buscar por título...">
            <select id="filtroGenero">
                <option value="todos">Todos los géneros</option>
                <option value="Shōnen">Shōnen</option>
                <option value="Seinen">Seinen</option>
                <option value="Shojo">Shojo</option>
            </select>
        </div>

        <div id="listaAnimes" class="contenedor-grid"></div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/fuse.js/dist/fuse.js"></script>
    <script src="js/buscador.js"></script>
</body>
<?php include "../footer/footer.php"; ?>
</html>