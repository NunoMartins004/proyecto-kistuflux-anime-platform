<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - KitsuFlux</title>
    <link rel="icon" href="../imagenes/kitsuflux.ico" type="image/x-icon">
    <link rel="stylesheet" href="../estilos.css">

</head>

<body class="login-body">

    <?php
    include("../header/header.php");
    ?>

    <main class="login-wrapper">
        <div class="login-container">
            <div class="login-box">
                <h1>Kitsu<span>Flux</span></h1>
                <p>Identifícate para continuar</p>

                <?php if (isset($_GET['error'])): ?>
                    <div style="background-color: rgba(255, 0, 0, 0.2); color: #ff4444; padding: 10px; border-radius: 5px; margin-bottom: 15px; border: 1px solid #ff4444; font-size: 0.9em; text-align: center;">
                        <?php
                        // No importa si el error es 'password' o 'user_not_found'
                        // Siempre mostramos el mismo mensaje genérico por seguridad.
                        echo "El usuario o la contraseña no son correctos.";
                        ?>
                    </div>
                <?php endif; ?>

                <form action="procesar_login.php" method="POST">
                    <div class="input-group">
                        <input type="text" name="username" required placeholder=" ">
                        <label>Nombre de usuario</label>
                    </div>

                    <div class="input-group">
                        <input type="password" name="password" required placeholder=" ">
                        <label>Contraseña</label>
                    </div>

                    <button type="submit" class="btn-fuego">ENTRAR</button>
                </form>

                <div class="login-footer">
                    ¿No tienes cuenta? <a href="../registro/registro.php">Regístrate aquí</a>
                </div>
            </div>
        </div>

    </main>

</body>

</html>