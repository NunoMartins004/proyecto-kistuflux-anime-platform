CREATE DATABASE IF NOT EXISTS AnimeApp;
USE AnimeApp;

CREATE TABLE Usuario (
    ID_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    correo VARCHAR(150) UNIQUE NOT NULL,
    contrasena VARCHAR(255) NOT NULL,
    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP,
    puntos_totales INT DEFAULT 0,
    puntos_ganar INT DEFAULT 0,
    es_premium TINYINT(1) DEFAULT 0,
    foto_perfil VARCHAR(255) DEFAULT 'img/default-user.png'
);

CREATE TABLE Rol (
    ID_rol INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) UNIQUE NOT NULL
);

CREATE TABLE UsuarioRol (
    ID_usuario INT NOT NULL,
    ID_rol INT NOT NULL,
    PRIMARY KEY (ID_usuario, ID_rol),
    FOREIGN KEY (ID_usuario) REFERENCES Usuario(ID_usuario)
        ON DELETE CASCADE,
    FOREIGN KEY (ID_rol) REFERENCES Rol(ID_rol)
        ON DELETE CASCADE
);

CREATE TABLE Anime (
    ID_anime INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(200) NOT NULL,
    descripcion TEXT,
    fecha_estreno DATE,
    fecha_subida TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    genero varchar(50) DEFAULT NULL
);

INSERT INTO Anime (ID_anime, titulo, descripcion, fecha_estreno) VALUES 
(1, 'One Piece', 'Luffy busca el tesoro legendario.', '1999-10-20'),
(2, 'Naruto', 'Un ninja que busca ser Hokage.', '2002-10-03');

INSERT INTO Anime (titulo, descripcion,fecha_estreno) 
VALUES ('Shingeki no Kyojin', 'La humanidad vive encerrada en ciudades rodeadas por murallas para protegerse de los Titanes. Eren Jaeger busca venganza tras perder su hogar.','2013-04-07');

INSERT INTO Anime (titulo, descripcion, fecha_estreno) 
VALUES ('Wind Breaker', 'Haruka Sakura no quiere saber nada de los débiles, solo le interesan los más fuertes. Acaba de empezar en el instituto Furin, una escuela de delincuentes que ahora son héroes protectores.', '2024-04-05');

INSERT INTO Anime (titulo, descripcion, fecha_estreno,genero) VALUES
('Kimetsu no Yaiba', 'Cazadores de demonios en una misión de venganza.', '2019-04-06', 'Shōnen'),
('Vinland Saga', 'Un joven vikingo busca venganza en medio de guerras épicas.', '2019-07-07', 'Seinen'),
('Your Lie in April', 'Un prodigio del piano recupera su música gracias a una violinista.', '2014-10-09',  'Shōnen'),
('Fullmetal Alchemist Brotherhood', 'Dos hermanos usan la alquimia para recuperar sus cuerpos.', '2009-04-05', 'Shōnen'),
('Boku no Kokoro no Yabai Yatsu', 'Un estudiante solitario se acerca a la chica más popular.', '2023-04-01' ,'Shojo');

CREATE TABLE ImagenesAnime (
    ID_imagen INT AUTO_INCREMENT PRIMARY KEY,
    ID_anime INT NOT NULL,
    url_imagen VARCHAR(255) NOT NULL,
    es_banner BOOLEAN DEFAULT FALSE,
    fecha_subida TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_anime_imagenes
    FOREIGN KEY (ID_anime) REFERENCES Anime(ID_anime)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

INSERT INTO ImagenesAnime (ID_anime, url_imagen, es_banner) 
VALUES (2, 'imagenes/naruto.jpg', TRUE);

INSERT INTO ImagenesAnime (ID_anime, url_imagen, es_banner) 
VALUES (2, 'imagenes/NarutoEpisodios.jpg', FALSE);

INSERT INTO ImagenesAnime (ID_anime, url_imagen, es_banner) 
VALUES (1, 'imagenes/One_Piece.png', TRUE);

INSERT INTO ImagenesAnime (ID_anime, url_imagen, es_banner) 
VALUES (1, 'imagenes/OnePiecePoster.jpg', FALSE);

INSERT INTO ImagenesAnime (ID_anime, url_imagen, es_banner) 
VALUES (3, 'imagenes/Shingeki.jpg', TRUE);

INSERT INTO ImagenesAnime (ID_anime, url_imagen, es_banner) 
VALUES (4, 'imagenes/Wind.jpg', TRUE);

INSERT INTO ImagenesAnime (ID_anime, url_imagen, es_banner) VALUES
(5, 'imagenes/kimetsu_no_yaiba.jpg', FALSE),
(6, 'imagenes/vinland.jpg', FALSE),
(7, 'imagenes/lieapril.jpg', FALSE),
(8, 'imagenes/fullmetal.jpeg', FALSE),
(9, 'imagenes/kokoro.jpeg', FALSE);

INSERT INTO ImagenesAnime (ID_anime, url_imagen, es_banner) VALUES
(9, 'imagenes/KokoroBanner.webp', TRUE);

INSERT INTO ImagenesAnime (ID_anime, url_imagen, es_banner) VALUES
(6, 'imagenes/VinlandBanner.webp', TRUE);

CREATE TABLE Episodio (
    ID_episodio INT AUTO_INCREMENT PRIMARY KEY,
    ID_anime INT NOT NULL,
    titulo VARCHAR(200) NOT NULL,
    numero_episodio INT NOT NULL,
    fecha_estreno DATE,
    fecha_publicacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    url_video VARCHAR(255),
    FOREIGN KEY (ID_anime) REFERENCES Anime(ID_anime)
        ON DELETE CASCADE,
    UNIQUE KEY registro_unico_episodio (ID_anime, numero_episodio)
);

INSERT INTO Episodio (ID_anime, numero_episodio, titulo, fecha_estreno, url_video) 
VALUES 
((SELECT ID_anime FROM Anime WHERE titulo = 'One Piece'), 1, '¡Soy Luffy! ¡El hombre que se convertirá en el Rey de los Piratas!', '1999-10-20', 'videos/op_01.mp4'),
((SELECT ID_anime FROM Anime WHERE titulo = 'One Piece'), 2, '¡Aparece el gran espadachín! El cazador de piratas, Roronoa Zoro', '1999-11-17', 'videos/op_02.mp4');

INSERT INTO Episodio (ID_anime, numero_episodio, titulo, fecha_estreno, url_video) 
VALUES 
((SELECT ID_anime FROM Anime WHERE titulo = 'Naruto'), 1, '¡Entra Naruto Uzumaki!', '2002-10-03', 'videos/naruto_01.mp4'),
((SELECT ID_anime FROM Anime WHERE titulo = 'Naruto'), 2, '¡Mi nombre es Konohamaru!', '2002-10-10', 'videos/naruto_02.mp4');

SET @id_naruto = (SELECT ID_anime FROM Anime WHERE titulo = 'Naruto' LIMIT 1);
SET @id_onepiece = (SELECT ID_anime FROM Anime WHERE titulo = 'One Piece' LIMIT 1);

INSERT INTO Episodio (ID_anime, numero_episodio, titulo, fecha_estreno, url_video) VALUES 
(@id_naruto, 3, '¡Sasuke y Sakura: ¿Amigos o enemigos?!', '2002-10-17', 'videos/naruto_03.mp4'),
(@id_naruto, 4, '¡Prueba de supervivencia! El examen de los cascabeles', '2002-10-24', 'videos/naruto_04.mp4'),
(@id_naruto, 5, '¡¿Fallamos?! La decisión de Kakashi', '2002-10-31', 'videos/naruto_05.mp4'),
(@id_naruto, 6, '¡Misión importante! ¡Rumbo al País de las Olas!', '2002-11-07', 'videos/naruto_06.mp4'),
(@id_naruto, 7, '¡El asesino de la niebla!', '2002-11-14', 'videos/naruto_07.mp4'),
(@id_naruto, 8, '¡El juramento del dolor!', '2002-11-21', 'videos/naruto_08.mp4'),
(@id_naruto, 9, '¡Kakashi: El guerrero del Sharingan!', '2002-11-28', 'videos/naruto_09.mp4');

INSERT INTO Episodio (ID_anime, numero_episodio, titulo, fecha_estreno, url_video) VALUES 
(@id_onepiece, 3, '¡Morgan contra Luffy! ¿Quién es esa guapa muchacha?', '1999-11-24', 'videos/op_03.mp4'),
(@id_onepiece, 4, '¡El pasado de Luffy! ¡Aparece el Pelirrojo Shanks!', '1999-12-08', 'videos/op_04.mp4'),
(@id_onepiece, 5, '¡El temible poder del Capitán Buggy!', '1999-12-15', 'videos/op_05.mp4'),
(@id_onepiece, 6, '¡Situación desesperada! Mohji el domador de fieras contra Luffy', '1999-12-29', 'videos/op_06.mp4'),
(@id_onepiece, 7, '¡Gran duelo! ¡Zoro el espadachín contra Cabaji el acróbata!', '2000-01-05', 'videos/op_07.mp4'),
(@id_onepiece, 8, '¡¿Quién ganará?! ¡Ajuste de cuentas entre los poderes de la fruta del diablo!', '2000-01-12', 'videos/op_08.mp4'),
(@id_onepiece, 9, '¡¿Capitán mentiroso?! El valiente Usopp', '2000-01-19', 'videos/op_09.mp4'),
(@id_onepiece, 10, '¡El hombre más raro del mundo! Jango el hipnotizador', '2000-01-26', 'videos/op_10.mp4'),
(@id_onepiece, 11, '¡Descubriendo la conspiración! El mayordomo pirata, Capitán Kuro', '2000-02-02', 'videos/op_11.mp4'),
(@id_onepiece, 12, '¡Batalla en la cuesta! ¡Luffy contra los piratas del Gato Negro!', '2000-02-09', 'videos/op_12.mp4');

INSERT INTO Episodio (ID_anime, numero_episodio, titulo, fecha_estreno, url_video) 
VALUES (4, 1, 'Sakura y el Instituto Furin', '2024-04-05', 'videos/windbreaker_01.mp4');

INSERT INTO Episodio (ID_anime, numero_episodio, titulo, fecha_estreno, url_video) 
VALUES (6, 1, 'En algún lugar de la nada', '2019-07-07', 'videos/vinlandsaga_01.mp4');

CREATE TABLE Opening (
    ID_opening INT AUTO_INCREMENT PRIMARY KEY,
    ID_anime INT NOT NULL,
    titulo VARCHAR(200),
    musicaOP VARCHAR(200),
    url_audio VARCHAR(255),
    duracion INT,
    FOREIGN KEY (ID_anime) REFERENCES Anime(ID_anime)
        ON DELETE CASCADE
);

CREATE TABLE Comentario (
    ID_comentario INT AUTO_INCREMENT PRIMARY KEY,
    ID_usuario INT NOT NULL,
    ID_episodio INT NOT NULL,
    contenido TEXT NOT NULL,
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ID_usuario) REFERENCES Usuario(ID_usuario)
        ON DELETE CASCADE,
    FOREIGN KEY (ID_episodio) REFERENCES Episodio(ID_episodio)
        ON DELETE CASCADE
);

CREATE TABLE ChatGeneralMensaje (
    ID_mensaje INT AUTO_INCREMENT PRIMARY KEY,
    ID_usuario INT NOT NULL,
    contenido TEXT NOT NULL,
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ID_usuario) REFERENCES Usuario(ID_usuario)
        ON DELETE CASCADE
);

CREATE TABLE Sigue (
    ID_usuario INT NOT NULL,
    ID_anime INT NOT NULL,
    fecha_inicio DATE,
    PRIMARY KEY (ID_usuario, ID_anime),
    FOREIGN KEY (ID_usuario) REFERENCES Usuario(ID_usuario)
        ON DELETE CASCADE,
    FOREIGN KEY (ID_anime) REFERENCES Anime(ID_anime)
        ON DELETE CASCADE
);

CREATE TABLE CalendarioUsuario (
    ID_usuario INT NOT NULL,
    ID_anime INT NOT NULL,
    dia_semana ENUM('L','M','X','J','V','S','D') NOT NULL,
    estado VARCHAR(50),
    PRIMARY KEY (ID_usuario, ID_anime, dia_semana),
    FOREIGN KEY (ID_usuario) REFERENCES Usuario(ID_usuario)
        ON DELETE CASCADE,
    FOREIGN KEY (ID_anime) REFERENCES Anime(ID_anime)
        ON DELETE CASCADE
);

CREATE TABLE VistoRecientemente (
    ID_usuario INT NOT NULL,
    ID_episodio INT NOT NULL,
    fecha_visualizacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (ID_usuario, ID_episodio),
    FOREIGN KEY (ID_usuario) REFERENCES Usuario(ID_usuario)
        ON DELETE CASCADE,
    FOREIGN KEY (ID_episodio) REFERENCES Episodio(ID_episodio)
        ON DELETE CASCADE
);

CREATE TABLE Juego (
    ID_juego INT AUTO_INCREMENT PRIMARY KEY,
    tipoJuego VARCHAR(100),
    descripcion TEXT
);

CREATE TABLE PuntuacionJuego (
    ID_puntuacion INT AUTO_INCREMENT PRIMARY KEY,
    ID_usuario INT NOT NULL,
    ID_juego INT NOT NULL,
    puntos INT NOT NULL,
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ID_usuario) REFERENCES Usuario(ID_usuario)
        ON DELETE CASCADE,
    FOREIGN KEY (ID_juego) REFERENCES Juego(ID_juego)
        ON DELETE CASCADE
);

INSERT INTO Rol (nombre) VALUES
('administrador'),
('moderador'),
('usuario'),
('suscrito');

-- 2. Creamos una tabla para guardar el historial de pagos
CREATE TABLE pagos (
  ID_pago int(11) NOT NULL AUTO_INCREMENT, 
  ID_usuario int(11) NOT NULL,             
  id_transaccion varchar(100) NOT NULL,    
  monto decimal(10,2) NOT NULL,            
  fecha timestamp DEFAULT current_timestamp(),
  PRIMARY KEY (ID_pago),
  FOREIGN KEY (ID_usuario) REFERENCES usuario (ID_usuario) ON DELETE CASCADE
);
