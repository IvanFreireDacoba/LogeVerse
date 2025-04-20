function onDomContentLoaded() {
    document.getElementById("seleccionPropuesta").addEventListener("change", (event) => {
        
        $formularios = document.getElementById("formularios");

        //Ocultar todos los divisores hijos de $formularios
        Array.from(formularios.children).forEach((child) => {
            child.setAttribute("hidden", true);
        });

        //Mostrar solo el divisor seleccionado en el evento
        const seleccionado = formularios.querySelector(`#${event.target.value}`);
        if (seleccionado) {
            console.log(event.target.value);
            
            seleccionado.removeAttribute("hidden");
        }
    });

    document.removeEventListener("DOMContentLoaded", onDomContentLoaded);
}

document.addEventListener("DOMContentLoaded", onDomContentLoaded);