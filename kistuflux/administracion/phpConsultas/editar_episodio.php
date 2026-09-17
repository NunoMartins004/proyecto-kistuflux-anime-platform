<?php
// phpConsultas/editar_episodio.php

include("../../conexion.php");

// Usamos tu archivo separado para traer la lista de animes
$animes = include("consulta_animes.php");

$episodio = null;

try {
    if (!isset($_GET['id'])) {
        throw new Exception("ID de episodio no proporcionado.");
    }

    $id = intval($_GET['id']);
    
    // Consultamos los datos actuales de este episodio concreto
    $sql = "SELECT * FROM Episodio WHERE ID_episodio = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $episodio = $result->fetch_assoc();

    if (!$episodio) {
        throw new Exception("Episodio no encontrado.");
    }

} catch (Exception $e) {
    echo "<div style='color:red; background:rgba(255,0,0,0.1); padding:15px;'>Error: " . $e->getMessage() . "</div>";
    exit;
}
?>

<div class="contenedor-edicion-ajax">
    <h2>Editar Episodio</h2>
    
    <form id="formEpisodio">
        <input type="hidden" name="ID_episodio" value="<?php echo $episodio['ID_episodio']; ?>">

        <div class="form-group">
            <label>Seleccionar Anime:</label>
            <select name="ID_anime" required>
                <?php foreach ($animes as $anime): ?>
                    <option value="<?php echo $anime['ID_anime']; ?>" 
                        <?php echo ($anime['ID_anime'] == $episodio['ID_anime']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($anime['titulo']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Título del Episodio:</label>
            <input type="text" name="titulo" 
                   value="<?php echo htmlspecialchars($episodio['titulo']); ?>" 
                   placeholder="Ej: El despertar del sharingan" required>
        </div>

        <div class="form-group">
            <label>Número de Episodio:</label>
            <input type="number" name="numero_episodio" min="1" 
                   value="<?php echo $episodio['numero_episodio']; ?>" required>
        </div>

        <div class="form-group">
            <label>Fecha de Estreno:</label>
            <input type="date" name="fecha_estreno" 
                   value="<?php echo $episodio['fecha_estreno']; ?>">
        </div>

        <div class="form-group">
            <label>Ruta del Video (URL o archivo):</label>
            <input type="text" name="url_video" 
                   value="<?php echo htmlspecialchars($episodio['url_video']); ?>" 
                   placeholder="Ej: videos/capitulo1.mp4">
        </div>

        <button type="submit" class="btn-save">Actualizar Episodio</button>
        <button type="button" id="btnCancelar" class="btn-back">Cancelar</button>
    </form>
</div>