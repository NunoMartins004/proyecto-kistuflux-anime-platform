<div class="contenedor-edicion-ajax">
    <h2>Insertar Nuevo Anime</h2>
    
    <form id="formAnime">
        <div class="form-group">
            <label>Imagen del Anime:</label>
            <input type="file" name="nueva_imagen" accept="image/*" required>
        </div>

        <div class="form-group">
            <label>Título:</label>
            <input type="text" name="titulo" placeholder="Ej: Naruto Shippuden" required>
        </div>

        <div class="form-group">
            <label>Descripción:</label>
            <textarea name="descripcion" rows="5" placeholder="Escribe la sinopsis..."></textarea>
        </div>

        <div class="form-group">
            <label>Tipo:</label>
            <select name="es_banner">
                <option value="0">Portada (Normal)</option>
                <option value="1">Banner (Slider)</option>
            </select>
        </div>

        <button type="submit" class="btn-save">Crear Anime</button>
        <button type="button" id="btnCancelar" class="btn-back">Cancelar</button>
    </form>
</div>