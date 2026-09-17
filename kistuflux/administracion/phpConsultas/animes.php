<?php
// 1. Conexión (ajusta la ruta según tu estructura)
include("../conexion.php"); 

// 2. La consulta pura que pediste
$sql = "SELECT a.ID_anime, a.titulo, a.descripcion, i.url_imagen, i.es_banner 
        FROM Anime a 
        INNER JOIN imagenesAnime i ON a.ID_anime = i.ID_anime 
        ORDER BY a.ID_anime DESC";

$result = $conn->query($sql);
$listaAnimes = [];

while($row = $result->fetch_assoc()) {
    $listaAnimes[] = $row;
}

// 3. Pasamos los datos de PHP a una variable Global de JavaScript
echo "<script> const DATOS_ANIMES = " . json_encode($listaAnimes) . "; </script>";

// 4. Llamamos al archivo JS que pintará la tabla (este archivo lo creas tú aparte)
echo "<script src='js/animes.js'></script>";
?>