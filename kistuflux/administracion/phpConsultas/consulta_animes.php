<?php
// 1. Conexión a la base de datos
include("../../conexion.php");

$animes = [];

try {
    // 2. Consultamos los animes para llenar el SELECT
    // Solo necesitamos el ID y el Título
    $sql = "SELECT ID_anime, titulo FROM Anime ORDER BY titulo ASC";
    $result = $conn->query($sql);

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $animes[] = $row;
        }
    }
} catch (Exception $e) {
    // Si falla la conexión, mostramos el error por consola para no romper el HTML
    echo "<script>console.error('Error al cargar animes: " . addslashes($e->getMessage()) . "');</script>";
}
return $animes;
?>