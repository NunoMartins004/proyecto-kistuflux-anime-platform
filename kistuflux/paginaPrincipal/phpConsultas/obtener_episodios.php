<?php
require_once("../../conexion.php");


$sql = "SELECT 
            A.titulo AS nombre_anime, 
            E.titulo AS nombre_episodio, 
            E.numero_episodio, 
            I.url_imagen,
            E.fecha_publicacion,
            E.url_video
        FROM Episodio E
        INNER JOIN Anime A ON E.ID_anime = A.ID_anime
        INNER JOIN ImagenesAnime I ON A.ID_anime = I.ID_anime
        WHERE I.es_banner = 0 
        ORDER BY E.fecha_publicacion DESC, E.numero_episodio DESC
        LIMIT 21";

$resultado = $conn->query($sql);
$episodios = [];

if($resultado && $resultado->num_rows > 0){
    while($row = $resultado->fetch_assoc()){
        $episodios[] = $row;
    }
}


header('Content-Type: application/json');
echo json_encode($episodios);
?>