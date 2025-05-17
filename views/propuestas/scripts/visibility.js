function onDomContentLoaded() {
    document.getElementById("seleccionPropuesta").addEventListener("change", (event) => {
        
        $formularios = document.getElementById("formularios");

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

    document.removeEventListener("DOMContentLoaded", onDomContentLoaded);
}

document.addEventListener("DOMContentLoaded", onDomContentLoaded);