import { setAttributes } from './atributeSelector.js';

export function loadedDomRz() {
    // =================== GESTIÓN DE LAS RAZAS ======================

    /*
    <input id="estadoRazas" name="raza" data-status="1" data-max="' . count($razas) . '" value="1" hidden></p>';

    <div class='razaContainer' id='raza_" . $raza["id"] . "' hidden>
        <p class='nombreRaza'>{$raza->getNombre()}</p>
        <img class='imgRaza' src='{$raza->getImagen()}'>
    </div>
    */

    // ================= Preparación para la primera vez ==========================
    //Obtener la raza inicial
    let elemento_init = document.getElementsByClassName("razaContainer")[0];
    //Mostrar la raza inicial
    elemento_init.removeAttribute("hidden");
    //Actualizar el input
    let id_raza = elemento_init.getAttribute("id").slice(5);
    document.getElementById("estadoRazas").setAttribute("value", id_raza);
    //Actualizar los atributos
    setAttributes(id_raza);

    // ================= Listener para el botón de raza previa ======================
    document.getElementById("prevRace").addEventListener("click", () => {
        const estado = parseInt(document.getElementById("estadoRazas").getAttribute("value"));
        const max = parseInt(document.getElementById("estadoRazas").getAttribute("data-max"));
        const elementos = document.getElementsByClassName("razaContainer");
        //Ocultar el elemento actual
        document.getElementById("raza_" + estado).setAttribute("hidden", null);
        //Si el estado actual es la primera raza, mostrar la última
        let nuevoEstado = (estado === 1) ? max : estado - 1;
        let elemento = elementos[nuevoEstado - 1];  //Restamos 1 para coincidir con el índice del array
        //Mostrar el elemento correspondiente
        elemento.removeAttribute("hidden");
        //Actualizar el estado
        document.getElementById("estadoRazas").setAttribute("value", nuevoEstado);
        //Actualizar los atributos
        setAttributes(nuevoEstado);
    });

    // ================ Listener para el botón de raza siguiente =====================
    document.getElementById("nextRace").addEventListener("click", () => {
        const estado = parseInt(document.getElementById("estadoRazas").getAttribute("value"));
        const max = parseInt(document.getElementById("estadoRazas").getAttribute("data-max"));
        const elementos = document.getElementsByClassName("razaContainer");
        //Ocultar el elemento actual
        document.getElementById("raza_" + estado).setAttribute("hidden", null);
        //Si el estado actual es la última raza, mostrar la primera
        let nuevoEstado = (estado === max) ? 1 : estado + 1;
        let elemento = elementos[nuevoEstado - 1];  //Restamos 1 para coincidir con el índice del array
        //Mostrar el elemento correspondiente
        elemento.removeAttribute("hidden");
        //Actualizar el estado
        document.getElementById("estadoRazas").setAttribute("value", nuevoEstado);
        //Actualizar los atributos
        setAttributes(nuevoEstado);
    });

    //Eliminar el listener del DOMContentLoaded (JScript no gestiona esto de manera automática)
    document.removeEventListener("DOMContentLoaded", loadedDomRz);
}