<?php
include '../conexion.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // 1. Recogemos y limpiamos los datos
    $nombre = mysqli_real_escape_string($conn, $_POST['username']);
    $correo = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // --- NUEVA BÚSQUEDA PREVIA ---
    $sqlCheck = "SELECT nombre FROM Usuario WHERE nombre = ? LIMIT 1";
    $stmtCheck = mysqli_prepare($conn, $sqlCheck);
    mysqli_stmt_bind_param($stmtCheck, "s", $nombre);
    mysqli_stmt_execute($stmtCheck);
    mysqli_stmt_store_result($stmtCheck);

    if (mysqli_stmt_num_rows($stmtCheck) > 0) {
        // Si el usuario ya existe, redirigimos antes de hacer nada más
        header("Location: registro.php?error=user_exists");
        exit();
    }
    // -----------------------------

    // 2. Gestión de la Imagen (Solo se ejecuta si el usuario no existe)
    $nombre_tmp = $_FILES['foto_perfil']['tmp_name'];
    $nombre_real = $_FILES['foto_perfil']['name'];
    $extension = strtolower(pathinfo($nombre_real, PATHINFO_EXTENSION));
    $formatos_ok = array("jpg", "jpeg", "png", "webp");
    
    $ruta_bd = "img/default-user.png"; 

    if (!empty($nombre_tmp) && in_array($extension, $formatos_ok)) {
        $user_limpio = preg_replace('/[^A-Za-z0-9]/', '', $nombre);
        $nuevo_nombre = $user_limpio . "_" . time() . "." . $extension;
        $destino = "../header/img/" . $nuevo_nombre;
        
        if (move_uploaded_file($nombre_tmp, $destino)) {
            $ruta_bd = $destino;
        }
    }

    // 3. INICIO DE TRANSACCIÓN 
    mysqli_begin_transaction($conn);

    try {
        // A) Insertamos en la tabla Usuario
        $sqlUser = "INSERT INTO Usuario (nombre, correo, contrasena, foto_perfil) VALUES (?, ?, ?, ?)";
        $stmtUser = mysqli_prepare($conn, $sqlUser);
        mysqli_stmt_bind_param($stmtUser, "ssss", $nombre, $correo, $password, $ruta_bd);
        
        if (!mysqli_stmt_execute($stmtUser)) {
            throw new Exception(mysqli_error($conn), mysqli_errno($conn));
        }
        
        $id_nuevo_usuario = mysqli_insert_id($conn);

        // B) Buscamos el ID del rol 'usuario'
        $sqlRol = "SELECT ID_rol FROM Rol WHERE nombre = 'usuario' LIMIT 1";
        $resRol = mysqli_query($conn, $sqlRol);
        $filaRol = mysqli_fetch_assoc($resRol);
        
        if (!$filaRol) {
            throw new Exception("El rol 'usuario' no existe en la tabla Rol.");
        }
        
        $id_rol_usuario = $filaRol['ID_rol'];

        // C) Insertamos en la tabla intermedia UsuarioRol
        $sqlAsig = "INSERT INTO UsuarioRol (ID_usuario, ID_rol) VALUES (?, ?)";
        $stmtAsig = mysqli_prepare($conn, $sqlAsig);
        mysqli_stmt_bind_param($stmtAsig, "ii", $id_nuevo_usuario, $id_rol_usuario);
        mysqli_stmt_execute($stmtAsig);

        mysqli_commit($conn);
        header("Location: ../login/login.php?registro=exitoso");
        exit();

    } catch (Exception $e) {
        mysqli_rollback($conn);
        
        $codigo_error = $e->getCode();
        $mensaje_db = $e->getMessage();

        if ($codigo_error == 1062) {
            // Esta parte queda como respaldo por si se registra alguien al mismo milisegundo
            if (strpos($mensaje_db, 'nombre') !== false || strpos($mensaje_db, $nombre) !== false) {
                header("Location: registro.php?error=user_exists");
            } else {
                header("Location: registro.php?error=email_exists");
            }
        } else {
            header("Location: registro.php?error=falla");
        }
        exit();
    }

    mysqli_close($conn);
}
?>