export function habilidad_efecto() {
    const availableContainer = document.getElementById('habilidad_avaliable_efects');
    const selectedContainer = document.getElementById('habilidad_selected_efects');

    //Función para alternar el estado de seleccionado a no seleccionado
    function toggleSelected(div) {
        //Seleccionamos todos el input principal dentro del divisor
        const input = div.querySelector('input');
        //Seleccionamos el input del modificador
        const modificador = div.querySelector('.modificador');
        //Seleccionamos el divisor del modificador
        const divModificador = div.querySelector('.div_modificador');
        //Miramos si está en estado 'selected' o no
        const isSelected = div.classList.contains('selected');

        //Si no está seleccionado
        if (!isSelected) {
            //Lo movemos al div de seleccionados
            selectedContainer.appendChild(div);
            //Lo marcamos como seleccionado
            div.classList.add('selected');
            //Habilitamos el input
            if (input) input.disabled = false;
            if (modificador) {
                modificador.disabled = false;
                modificador.hidden = false;
            }
            if (divModificador) divModificador.removeAttribute("hidden");
        } else {
            //Lo volvemos a mandar al div de disponibles
            availableContainer.appendChild(div);
            //Eliminamos la marca de selección
            div.classList.remove('selected');
            //Volvemos a deshabilitar el input
            if (input) input.disabled = true;
            if (modificador) {
                modificador.disabled = true;
                modificador.hidden = true;
            }
            if (divModificador) divModificador.hidden = true;
        }
    }

    //Listener para cada div de $efecto
    document.querySelectorAll('.habilidad_efecto').forEach(div => {
        div.addEventListener('click', () => toggleSelected(div));
    });

    //Listener para el checkbox
    const checkbox = document.querySelector("#checkbox_habilidad_efectos input[type='checkbox']");
    const efectSection = document.getElementById("habilidad_efectos_select");

    // Aplicar estado inicial
    if (checkbox.checked) {
        efectSection.removeAttribute("hidden");
        efectSection.classList.add("habilidad_efectos_select_style");
    } else {
        efectSection.setAttribute("hidden", true);
        efectSection.classList.remove("habilidad_efectos_select_style");
    }

    checkbox.addEventListener("change", () => {
        if (checkbox.checked) {
            efectSection.removeAttribute("hidden");
            efectSection.classList.add("habilidad_efectos_select_style");
        } else {
            efectSection.setAttribute("hidden", true);
            efectSection.classList.remove("habilidad_efectos_select_style");
        }
    });

    document.removeEventListener("DOMContentLoaded", habilidad_efecto);
}