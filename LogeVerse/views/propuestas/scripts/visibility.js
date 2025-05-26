export function visibility() {
    document.getElementById("seleccionPropuesta").addEventListener("change", () => {
        
        const formularios = document.getElementById("formularios");

        //Ocultar todos los divisores hijos de $formularios
        Array.from(formularios.children).forEach((child) => {
            child.setAttribute("hidden", true);
        });

        //Mostrar solo el divisor seleccionado en el evento
        const nombreSel = document.getElementById("seleccionPropuesta").value;
        const seleccionado = document.getElementById(nombreSel);
        if (seleccionado) {
            seleccionado.removeAttribute("hidden");
        }
    });

    const modificadores = document.getElementsByClassName("modificador");
    Array.from(modificadores).forEach(modificador => {
        modificador.addEventListener("click", (e) => {
            e.stopPropagation();
        })
    });

    document.removeEventListener("DOMContentLoaded", visibility);
}