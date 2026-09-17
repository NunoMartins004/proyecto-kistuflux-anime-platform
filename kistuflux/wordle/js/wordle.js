// Lista actualizada con animes de 6 LETRAS
const LISTA_ANIMES = [
    "BLEACH", "NARUTO", "DORORO", "ERASED", "LUPIN3", 
    "COWBOY", "TEKKON", "POKEMO", "PSYCHO", "ORANGE",
    "GANTZ0", "KAIJI1", "BANANA", "BEASTS", "ZOMBIE",
    "ANOHAN", "BTOOOM", "KASHER", "HELLSI", "TRIGUN"
];

const hoy = new Date();
const fechaHoy = hoy.getFullYear() + "-" + (hoy.getMonth() + 1) + "-" + hoy.getDate();
const fechaSemilla = hoy.getFullYear().toString() + hoy.getMonth().toString() + hoy.getDate().toString();
const indice = parseInt(fechaSemilla) % LISTA_ANIMES.length;
const PALABRA_OBJETIVO = LISTA_ANIMES[indice].toUpperCase();

let filaActual = 0;
const tablero = document.getElementById("tablero");
const input = document.getElementById("intento");
const mensaje = document.getElementById("mensaje");
const boton = document.getElementById("btn-comprobar");

function verificarEstadoJuego() {
    const ultimoJuego = localStorage.getItem("ultimoJuegoFecha_" + idUsuarioActual);
    if (ultimoJuego === fechaHoy) {
        input.disabled = true;
        boton.disabled = true;
        const tiempo = obtenerTiempoRestante();
        mensaje.innerHTML = `Ya has participado hoy. <br> Próximo anime en: <strong>${tiempo}</strong>`;
        return true;
    }
    return false;
}

function obtenerTiempoRestante() {
    const ahora = new Date();
    let proximo = new Date();
    proximo.setDate(ahora.getDate() + 1);
    proximo.setHours(0, 0, 0, 0);
    const diff = proximo - ahora;
    const horas = Math.floor(diff / (1000 * 60 * 60));
    const minutos = Math.floor((diff / (1000 * 60)) % 60);
    return `${horas}h ${minutos}m`;
}

// Inicializar tablero: Ahora 36 celdas (6x6)
if (tablero) {
    for (let i = 0; i < 36; i++) {
        let celda = document.createElement("div");
        celda.classList.add("celda");
        celda.id = "celda-" + i;
        tablero.appendChild(celda);
    }
    verificarEstadoJuego(); 
}

function comprobar() {
    const intento = input.value.toUpperCase();

    // Validación de 6 letras
    if (intento.length !== 6) {
        mensaje.innerText = "¡Deben ser 6 letras!";
        return;
    }

    if (!LISTA_ANIMES.includes(intento)) {
        mensaje.style.color = "#ff4d4d";
        mensaje.innerText = "Ese anime no está en nuestra lista de 6 letras.";
        return;
    }

    mensaje.innerText = ""; 

    // Bucle ajustado a 6 letras
    for (let i = 0; i < 6; i++) {
        const celda = document.getElementById("celda-" + (filaActual * 6 + i));
        const letra = intento[i];
        celda.innerText = letra;

        if (letra === PALABRA_OBJETIVO[i]) {
            celda.classList.add("verde");
        } else if (PALABRA_OBJETIVO.includes(letra)) {
            celda.classList.add("amarillo");
        } else {
            celda.classList.add("gris");
        }
    }

    if (intento === PALABRA_OBJETIVO || filaActual === 5) {
        localStorage.setItem("ultimoJuegoFecha_" + idUsuarioActual, fechaHoy);
        input.disabled = true;
        boton.disabled = true;
        
        const tiempo = obtenerTiempoRestante();
        if (intento === PALABRA_OBJETIVO) {
            mensaje.style.color = "#538d4e";
            mensaje.innerHTML = `¡Acertaste! 🎉 <br> Tiempo para el siguiente: <strong>${tiempo}</strong>`;
        } else {
            mensaje.innerHTML = `Perdiste. Era: ${PALABRA_OBJETIVO} <br> Nuevo intento en: <strong>${tiempo}</strong>`;
        }
    } else {
        filaActual++;
    }
    input.value = "";
}