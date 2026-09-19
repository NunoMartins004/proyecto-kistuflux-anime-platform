# 🎬 KitsuFlux - Plataforma Web de Streaming de Anime

**KitsuFlux** es una plataforma web completa de streaming de anime inspirada en *Crunchyroll*, desarrollada en pareja como **Trabajo de Fin de Grado (TFG)**. El proyecto combina un catálogo interactivo, gestión avanzada de usuarios, pasarela de pago real, panel de administración y minijuegos temáticos integrados.

---

## 📽️ Demostración y Documentación

* 📺 **[Ver Vídeo de Demostración en YouTube](https://youtu.be/hqxXNSRS85Q)** *(Recomendado para ver la plataforma en funcionamiento sin necesidad de instalarla localmente)*.
* 📄 **[Ver Memoria Técnica Completa (PDF)](./memoria/MEMORIA%20DEL%20PROYECTO%20TECNICO_25_26%20Kitsuflux%20Nuno.pdf)** *(Documentación detallada del proyecto disponible en la carpeta `/memoria`)*.

---

## 👥 División del Trabajo y Desarrollo

El proyecto fue desarrollado de forma colaborativa dividiendo las responsabilidades principales entre frontend y backend:

* **Frontend, Diseño y Lógica de Cliente (Mi parte):**
  * Diseño visual adaptativo e interfaz de usuario completa.
  * Desarrollo de la sección **Directorio de Animes** y la sección **Suscripción Premium**.
  * Creación e integración de los minijuegos **Anime Wordle** y **Anime Heardle**.
* **Backend, Arquitectura y Administración (Compañero):**
  * Diseño e implementación de la base de datos relacional.
  * Desarrollo del sistema de autenticación (Registro, Login, Control de sesiones y restricciones para usuarios no registrados).
  * Creación del **Panel de Administración** e integración del carrusel dinámico y catálogo de últimos episodios.

---

## ✨ Características Principales

* 🔍 **Buscador Inteligente (Directorio):** Búsqueda en tiempo real con tolerancia a fallos tipográficos gracias a la integración con `Fuse.js` (ej. al buscar *"ona piaza"* reconoce *"One Piece"*).
* 👑 **Suscripción Premium:** Pasarela de pago real integrada mediante `PayPal SDK` para adquirir membresías y eliminar publicidad.
* 🎮 **Minijuegos Temáticos (24h Cooldown):** 
  * **Anime Wordle:** Adivina el nombre del anime del día.
  * **Anime Heardle:** Adivina el *opening* escuchando fragmentos de audio progresivos (vía `YouTube IFrame API`).
  * *Nota: Ambos minijuegos requieren iniciar sesión y bloquean nuevas partidas durante 24h tras finalizar.*
* 🎠 **Carrusel y Novedades:** Slider dinámico en la portada con los últimos lanzamientos y animes destacados.
* 🔐 **Gestión de Usuarios y Roles:** Control de acceso que limita ciertas funciones (como jugar minijuegos) a usuarios visitantes.
* 🛠️ **Panel de Administración:** Gestión integral para añadir/editar animes, capítulos, moderación de usuarios y creación de cuentas administrativas.

---

## 🛠️ Tecnologías Utilizadas

### Lenguajes y Servidor
* **HTML5 & CSS3:** Estructuración y diseño adaptativo.
* **JavaScript (ES6+):** Lógica interactiva de minijuegos y pasarela de pago.
* **PHP:** Lenguaje de servidor para lógica de negocio y sesiones.
* **MySQL & Apache (XAMPP):** Base de datos relacional y servidor web local.

### Librerías y APIs Externas
* **Fuse.js:** Motor de búsqueda difusa para el catálogo.
* **PayPal SDK:** Pasarela de pago segura sin almacenamiento local de datos bancarios.
* **YouTube IFrame API:** Reproducción controlada de audio para la mecánica del *Heardle*.
* **Swiper.js:** Motor para los carruseles dinámicos y táctiles.
* **Jikan API (MyAnimeList):** Obtención automatizada de sinopsis, géneros y valoraciones de animes.

---

## 💻 Herramientas de Desarrollo

* **Visual Studio Code:** Editor principal con extensiones de depuración.
* **XAMPP & phpMyAdmin:** Administración de entornos locales Apache, MariaDB y tablas SQL.
* **Developer Tools:** Inspección DOM y depuración de eventos JavaScript.

---

## 🚀 Guía de Instalación Local

Si prefieres probar la aplicación en tu entorno local, sigue estos pasos:

1. **Requisitos previos:** Tener instalado y en ejecución **XAMPP** con los módulos **Apache** y **MySQL** activos.
2. **Importar la Base de Datos:**
   * Abre phpMyAdmin en tu navegador (`http://localhost/phpmyadmin/`).
   * Crea una nueva base de datos e importa el archivo `Base de datos.sql` incluido en el repositorio.
3. **Despliegue del Código:**
   * Descarga o clona este repositorio dentro de la carpeta `htdocs` de XAMPP (habitualmente en `C:\xampp\htdocs\`).
4. **Ejecutar la Plataforma:**
   * Abre el navegador e ingresa a la siguiente URL para cargar la portada:
     ```text
     http://localhost/proyecto/paginaPrincipal/paginaPrincipal.php
     ```
5. **Crear usuario Administrador:**
   * Para acceder al panel de administración, asigna manualmente los permisos correspondientes desde la tabla de usuarios en `phpMyAdmin`.
