
<?php include("consulta_animes.php"); ?>

<div class="contenedor-edicion-ajax">
    <h2>Insertar Nuevo Episodio</h2>
    
    <form id="formEpisodio">
        <div class="form-group">
            <label>Seleccionar Anime:</label>
            <select name="ID_anime" required>
                <option value="" disabled selected>-- Elige un anime --</option>
                <?php if (empty($animes)): ?>
                    <option value="" disabled>No se encontraron animes</option>
                <?php else: ?>
                    <?php foreach ($animes as $anime): ?>
                        <option value="<?php echo $anime['ID_anime']; ?>">
                            <?php echo htmlspecialchars($anime['titulo']); ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Título del Episodio:</label>
            <input type="text" name="titulo" placeholder="Ej: El despertar del sharingan" required>
        </div>

        <div class="form-group">
            <label>Número de Episodio:</label>
            <input type="number" name="numero_episodio" min="1" placeholder="Ej: 1" required>
        </div>

        <div class="form-group">
            <label>Fecha de Estreno:</label>
            <input type="date" name="fecha_estreno" value="<?php echo date('Y-m-d'); ?>">
        </div>

        <div class="form-group">
            <label>Ruta del Video (URL o archivo):</label>
            <input type="text" name="url_video" placeholder="Ej: videos/capitulo1.mp4">
        </div>

        <button type="submit" class="btn-save">Crear Episodio</button>
        <button type="button" id="btnCancelar" class="btn-back">Cancelar</button>
    </form>
</div>