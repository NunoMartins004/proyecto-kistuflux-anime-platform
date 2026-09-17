<?php
include("../../conexion.php");
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// 1. Modificamos la consulta para traer también el ID de la tabla de imágenes
$query = "SELECT a.*, i.es_banner, i.id_imagen, i.url_imagen 
          FROM Anime a 
          LEFT JOIN imagenesAnime i ON a.ID_anime = i.ID_anime 
          WHERE a.ID_anime = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$anime = $stmt->get_result()->fetch_assoc();
?>

<div class="contenedor-edicion-ajax">
    <h2>Editando: <?php echo htmlspecialchars($anime['titulo']); ?></h2>
    
    <form id="formAnime">
        <input type="hidden" name="id" value="<?php echo $anime['ID_anime']; ?>">
        <input type="hidden" name="id_imagen" value="<?php echo $anime['id_imagen']; ?>">
        
        <div class="form-group">
            <label>Imagen Actual:</label>
            <?php $ruta = $anime['url_imagen'] ? "../".$anime['url_imagen'] : "../img/placeholder.png"; ?>
            <img src="<?php echo $ruta; ?>" width="100" style="border-radius: 4px; border: 1px solid #444;">
        </div>

        <div class="form-group">
            <label>Nueva Imagen (dejar vacío para mantener la actual):</label>
            <input type="file" name="nueva_imagen" accept="image/*">
        </div>

        <div class="form-group">
            <label>Título:</label>
            <input type="text" name="titulo" value="<?php echo htmlspecialchars($anime['titulo']); ?>" required>
        </div>

        <div class="form-group">
            <label>Descripción:</label>
            <textarea name="descripcion" rows="5"><?php echo htmlspecialchars($anime['descripcion']); ?></textarea>
        </div>

        <div class="form-group">
            <label>Tipo:</label>
            <select name="es_banner">
                <option value="0" <?php echo $anime['es_banner'] == 0 ? 'selected' : ''; ?>>Portada</option>
                <option value="1" <?php echo $anime['es_banner'] == 1 ? 'selected' : ''; ?>>Banner</option>
            </select>
        </div>

        <button type="submit" class="btn-save">Guardar Cambios</button>
        <button type="button"  id="btnCancelar" class="btn-back">Cancelar</button>
    </form>
</div>