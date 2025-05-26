export function controlarPasivasRaza() {

    const idiomasRaza = Array.from(document.querySelectorAll(".raza_idioma"));
    const pasivasRaza = Array.from(document.querySelectorAll(".raza_pasiva"));

    const contenedorDisponibles = document.getElementById("race_avaliable_pasives");
    const contenedorSeleccionadas = document.getElementById("race_selected_pasives");

    idiomasRaza.forEach(idioma => {
        idioma.addEventListener("click", () => {
            toggleIdioma(idioma);
            pasivasRaza.forEach(pasiva => {
                if (pasiva.id_pasiva === idioma.id_pasiva) {
                    togglePasiva(pasiva);
                }
            })
        })
    });

    pasivasRaza.forEach(pasiva => {
        pasiva.addEventListener("click", () => {
            togglePasiva(pasiva);
            idiomasRaza.forEach(idioma => {
                if (idioma.id_pasiva === pasiva.id_pasiva) {
                    toggleIdioma(idioma);
                }
            });
        })
    });

    function togglePasiva(pasiva) {
        const input = pasiva.querySelector("input");
        if (input.disabled) {
            input.disabled = false;
            contenedorSeleccionadas.appendChild(pasiva);
        } else {
            input.disabled = true;
            contenedorDisponibles.appendChild(pasiva);
        }
    }

    function toggleIdioma(idioma) {
        const input = idioma.querySelector("input");
        idioma.classList.toggle("selected");
        if (input.disabled) {
            input.disabled = false;
        } else {
            input.disabled = true;
        }
    }

    document.removeEventListener("DOMContentLoaded", controlarPasivasRaza);
}