export function loadedDomCl() {
    // =================== GESTIÓN DE LAS CLASES ======================

    /*
    <input id="estadoClases" name="clase" data-max="' . count($clases) - 1 . '" value="" hidden>
    <div class='claseContainer' id='clase_" . $clase["id"] . "' hidden>
        <p class='nombreClase'>{$clase->getNombre()}</p>
        <img class='imgClase' src='{$clase->getImagen()}'>
    </div>"
    */
   
    // ================= Preparación para la primera vez ==========================
    //Obtener la clase inicial
    let elemento_init = document.getElementsByClassName("claseContainer")[0];
    //Mostrar la clase inicial
    elemento_init.removeAttribute("hidden");
    //Actualizar el input
    let id_clase = elemento_init.getAttribute("id").slice(6);
    document.getElementById("estadoClases").setAttribute("value", id_clase);

    // ================= Listener para el botón de clase previa ======================
    document.getElementById("prevClass").addEventListener("click", () => {
        const estado = parseInt(document.getElementById("estadoClases").getAttribute("value"));
        const max = parseInt(document.getElementById("estadoClases").getAttribute("data-max"));
        const elementos = document.getElementsByClassName("claseContainer");

        //Ocultar el elemento actual
        document.getElementById("clase_" + estado).setAttribute("hidden", null);

        //Si el estado actual es la primera clase, mostrar la última
        let nuevoEstado = (estado === 1) ? max : estado - 1;
        let elemento = elementos[nuevoEstado - 1];  //Restamos 1 para coincidir con el índice del array

        //Mostrar el elemento correspondiente
        elemento.removeAttribute("hidden");

        //Actualizar el estado
        document.getElementById("estadoClases").setAttribute("value", nuevoEstado);
    });

    // ================ Listener para el botón de clase siguiente =====================
    document.getElementById("nextClass").addEventListener("click", () => {
        const estado = parseInt(document.getElementById("estadoClases").getAttribute("value"));
        const max = parseInt(document.getElementById("estadoClases").getAttribute("data-max"));
        const elementos = document.getElementsByClassName("claseContainer");

        //Ocultar el elemento actual
        document.getElementById("clase_" + estado).setAttribute("hidden", null);

        //Si el estado actual es la última clase, mostrar la primera
        let nuevoEstado = (estado === max) ? 1 : estado + 1;
        let elemento = elementos[nuevoEstado - 1];  //Restamos 1 para coincidir con el índice del array

        //Mostrar el elemento correspondiente
        elemento.removeAttribute("hidden");

        //Actualizar el estado
        document.getElementById("estadoClases").setAttribute("value", nuevoEstado);
    });

    //Eliminar el listener del DOMContentLoaded (JScript no gestiona esto de manera automática)
    document.removeEventListener("DOMContentLoaded", loadedDomCl);
}