const OPENINGS_DIARIOS = [
    { nombre: "NARUTO", id: "4t__wczfpRI" },
    { nombre: "ONE PIECE", id: "YoeP9w5UIlg" },
    { nombre: "BLEACH", id: "wW9TwZdWpjw" },
    { nombre: "FRIEREN", id: "QoGM9hCxr4k" },
    { nombre: "AKIRA", id: "rubRpPgbY_w" }
];

const hoy = new Date();
const fechaHoy = hoy.getFullYear() + "-" + (hoy.getMonth() + 1) + "-" + hoy.getDate();
const fechaSemilla = hoy.getFullYear().toString() + hoy.getMonth().toString() + hoy.getDate().toString();
const indice = parseInt(fechaSemilla) % OPENINGS_DIARIOS.length;
const OPENING_OBJETIVO = OPENINGS_DIARIOS[indice];

let intentoActual = 1;
const tiempos = [0, 2, 4, 6, 8, 10, 12];
let player;

// Elementos (Estilo Wordle)
const botonHeardle = document.getElementById('btn-enviar-heardle');
const inputHeardle = document.getElementById('input-busqueda');
const volumenSlider = document.getElementById('volumen-slider');

function onYouTubeIframeAPIReady() {
    if (localStorage.getItem("ultimoHeardle_" + idUsuarioActual) === fechaHoy) {
        finalizarJuego(false);
        document.getElementById('mensaje-heardle').innerText = "Ya has participado hoy. ¡Vuelve mañana!";
        return;
    }

    player = new YT.Player('player', {
        height: '450',
        width: '800',
        videoId: OPENING_OBJETIVO.id,
        playerVars: {
            'controls': 0,
            'disablekb': 1,
            'rel': 0,
            'modestbranding': 1,
            'enablejsapi': 1,
            'origin': window.location.origin
        },
        events: {
            'onReady': (event) => {
                event.target.setVolume(30); 
                console.log("Audio listo");
            },
            'onError': (e) => console.error("Error YouTube:", e.data)
        }
    });
}

// Eventos de usuario
if (botonHeardle) {
    botonHeardle.addEventListener('click', comprobarOpening);
}

if (inputHeardle) {
    inputHeardle.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') comprobarOpening();
    });
}

if (volumenSlider) {
    volumenSlider.addEventListener('input', (e) => {
        if (player && typeof player.setVolume === 'function') {
            player.setVolume(e.target.value);
        }
    });
}

document.getElementById('btn-play').addEventListener('click', () => {
    if (!player || typeof player.playVideo !== 'function') {
        alert("Cargando audio...");
        return;
    }
    const barra = document.getElementById('progreso-actual');
    const tiempoMaximo = tiempos[intentoActual];
    player.seekTo(0);
    player.playVideo();
    barra.style.transition = "none";
    barra.style.width = "0%";
    setTimeout(() => {
        barra.style.transition = `width ${tiempoMaximo}s linear`;
        barra.style.width = "100%";
    }, 10);
    setTimeout(() => player.pauseVideo(), tiempoMaximo * 1000);
});

function comprobarOpening() {
    const seleccion = inputHeardle.value.toUpperCase().trim();
    const mensaje = document.getElementById('mensaje-heardle');

    if (seleccion === OPENING_OBJETIVO.nombre) {
        mensaje.style.color = "#538d4e"; 
        mensaje.innerHTML = `¡BRUTAL! 🎉 <br> Es <strong>${OPENING_OBJETIVO.nombre}</strong>.`;
        finalizarJuego(true);
        player.seekTo(0);
        player.playVideo();
    } else {
        intentoActual++;
        inputHeardle.classList.add("vibrar"); 
        setTimeout(() => inputHeardle.classList.remove("vibrar"), 500);
        if (intentoActual > 6) {
            mensaje.style.color = "#ff4d4d";
            mensaje.innerHTML = `Perdiste. 💀 Era: <strong>${OPENING_OBJETIVO.nombre}</strong>`;
            finalizarJuego(false);
        } else {
            mensaje.style.color = "#ff4d4d";
            mensaje.innerText = `❌ Intento ${intentoActual - 1}/6 fallido.`;
        }
    }
    inputHeardle.value = "";
}

function finalizarJuego(ganado) {
    document.getElementById('overlay-video').style.display = 'none';
    document.getElementById('btn-play').disabled = true;
    if(botonHeardle) botonHeardle.disabled = true;
    if(inputHeardle) inputHeardle.disabled = true;
    localStorage.setItem("ultimoHeardle_" + idUsuarioActual, fechaHoy);
}