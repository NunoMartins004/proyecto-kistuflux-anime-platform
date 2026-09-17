<?php
// phpConsultas/editar_usuario.php
include("../../conexion.php");

try {
    if (!isset($_GET['id'])) throw new Exception("ID no proporcionado.");
    $id = intval($_GET['id']);

    // 1. Obtener datos completos del usuario y su ID de rol actual
    $sql = "SELECT u.*, ur.ID_rol 
            FROM Usuario u 
            LEFT JOIN UsuarioRol ur ON u.ID_usuario = ur.ID_usuario 
            WHERE u.ID_usuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $usuario = $stmt->get_result()->fetch_assoc();

    if (!$usuario) throw new Exception("Usuario no encontrado.");

    // 2. Obtener roles para el desplegable
    $roles = $conn->query("SELECT * FROM Rol ORDER BY nombre ASC");

} catch (Exception $e) {
    echo "<div style='color:red;'>Error: " . $e->getMessage() . "</div>";
    exit;
}
?>

<div class="contenedor-edicion-ajax">
    <h2>Editar Perfil de Usuario</h2>
    
    <form id="formEditarUsuario">
        <input type="hidden" name="ID_usuario" value="<?php echo $usuario['ID_usuario']; ?>">

        <div class="form-group">
            <label>Nombre de Usuario:</label>
            <input type="text" name="nombre" value="<?php echo htmlspecialchars($usuario['nombre']); ?>" required>
        </div>

        <div class="form-group">
            <label>Correo Electrónico (No modificable):</label>
            <input type="email" value="<?php echo htmlspecialchars($usuario['correo']); ?>" readonly style="background: #333; color: #888; cursor: not-allowed;">
            <small>El correo no puede ser alterado por razones de seguridad.</small>
        </div>

        <div class="form-group">
            <label>Puntos Totales:</label>
            <input type="number" name="puntos_totales" value="<?php echo $usuario['puntos_totales']; ?>" min="0">
        </div>

        <div class="form-group">
            <label>Rol del Sistema:</label>
            <select name="ID_rol" required>
                <?php while($rol = $roles->fetch_assoc()): ?>
                    <option value="<?php echo $rol['ID_rol']; ?>" 
                        <?php echo ($rol['ID_rol'] == $usuario['ID_rol']) ? 'selected' : ''; ?>>
                        <?php echo ucfirst(htmlspecialchars($rol['nombre'])); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div style="margin-top: 20px;">
            <button type="submit" class="btn-save">Guardar Cambios</button>
            <button type="button" class="btn-back" id="btnCancelar">Cancelar</button>
        </div>
    </form>
</div>