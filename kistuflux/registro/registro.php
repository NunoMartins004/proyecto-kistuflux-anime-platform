<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - KitsuFlux</title>
    <link rel="icon" href="../imagenes/kitsuflux.ico" type="image/x-icon">
    <link rel="stylesheet" href="../estilos.css">

</head>

<body class="login-body">

    <?php include("../header/header.php"); ?>

    <div class="login-container">
        <div class="login-box">
            <h1>Kitsu<span>Registro</span></h1>
            <p>Crea tu cuenta y personaliza tu perfil</p>
            <?php if (isset($_GET['error'])): ?>
                <div style="background: rgba(255,0,0,0.2); color: #ff4444; padding: 10px; border: 1px solid #ff4444; border-radius: 5px; margin-bottom: 15px; text-align: center; font-size: 0.9em;">
                    <?php
                    switch ($_GET['error']) {
                        case 'user_exists':
                            echo "Nombre de usuario ya existente.";
                            break;
                        case 'email_exists':
                            echo "Este correo electrónico ya está en uso.";
                            break;
                        case 'falla':
                            echo "Hubo un error crítico. Inténtalo de nuevo más tarde.";
                            break;
                        default:
                            echo "Error al registrar la cuenta.";
                            break;
                    }
                    ?>
                </div>
            <?php endif; ?>

            <form action="procesar_registro.php" method="POST" enctype="multipart/form-data">

                <div class="input-group">
                    <input type="text" name="username" required placeholder=" ">
                    <label>Nombre de Usuario</label>
                </div>

                <div class="input-group">
                    <input type="email" name="email" required placeholder=" ">
                    <label>Correo Electrónico</label>
                </div>

                <div class="input-group">
                    <input type="password" name="password" required placeholder=" ">
                    <label>Contraseña</label>
                </div>

                <div class="file-input-container">
                    <label>Foto de Perfil:</label>
                    <input type="file" name="foto_perfil" accept="image/*">
                </div>

                <button type="submit" class="btn-fuego">REGISTRARSE</button>
            </form>

            <div class="login-footer">
                ¿Ya tienes cuenta? <a href="../login/login.php">Inicia sesión</a>
            </div>
        </div>
    </div>

</body>

</html>