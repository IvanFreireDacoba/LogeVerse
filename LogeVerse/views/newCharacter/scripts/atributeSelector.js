export function loadedDomAt() {
    // ============== Actualizar el total de puntos al cambiar el valor ================
    let atr_pts_inputs = document.getElementsByClassName("atr_pts");
    //Iteramos sobre todos los inputs y preparamos su listener, solo necesitamos actualizar su fila
    for (const input of atr_pts_inputs) {
        input.addEventListener("change", () => {
            updateAtributes();
        });
    }
    //Actualizar el total de puntos al cargar la página (Pueden entrar datos previos de POST)
    updateAtributes();
    //Eliminar el listener del DOMContentLoaded (JScript no gestiona esto de manera automática)
    document.removeEventListener("DOMContentLoaded", loadedDomAt);
}


//Muestra los atributos de la raza seleccionada y actualiza la tabla de atributos
export function setAttributes(id_raza) {
    /* =================== SET DE ATRIBUTOS POR RAZA ====================== */
    //Obtenemos los td de las filas que corresponden a los atributos de la raza
    let art_data_rows = document.getElementsByClassName("atr_raza");
    //Iteramos sobre ellos y cambiamos el textContent por el valor de la variable global
    //preestablecido desde el script del servidor
    for (const td of art_data_rows) {
        //Ponemos el contenido del td con el valor del atributo para la nueva raza
        td.textContent = atr_razas[id_raza][td.getAttribute("index")];
    }
    //Actualizamos el valor final de los atributos según los nuevos valores
    updateAtributes();
}

export function updateAtributes() {
    /* ================== ACTUALIZACIÓN DE ATRIBUTOS ===================== */
    //Obtenemos los inputs de cada uno de los atributos
    let atr_tot_inputs = document.getElementsByClassName("atr_input_for_js");
    //Declaramos las variables que almacenarán el valor de cada apartado del atributo
    let v_raza, v_dice, v_ptos, v_tot, v_ptos_consumidos = 0;
    //Obtenemos todas las filas de la tabla de atributos
    let atr_rows = document.getElementsByClassName("atr_row");
    //Iteramos sobre las filas realizando los cálculos pertinentes y estableciento el valor final
    //almacenamos el indice para poder saber a queé input corresponde cada fila
    let i = 0;
    for (const row of atr_rows) {
        v_raza = Number(row.getElementsByClassName("atr_raza")[0].textContent);
        v_dice = Number(row.getElementsByClassName("atr_dice")[0].textContent);
        v_ptos = Number(row.getElementsByClassName("atr_pts")[0].value);
        v_tot = v_raza + v_dice + v_ptos;
        v_ptos_consumidos += v_ptos;
        //Establecemos el valor total en la celda correspondiente
        row.getElementsByClassName("atr_total")[0].textContent = v_tot;
        //Establecemos el valor total en el input correspondiente
        atr_tot_inputs[i].setAttribute("value", v_tot);
        i++;
    }

    //Actualizamos el input de puntos de habilidad disponibles
    let p_ptos_habilidad = document.getElementById("ptos_habilidad_info");
    let input_ptos_habilidad = document.getElementById("ptos_habilidad");
    p_ptos_habilidad.textContent = p_ptos_habilidad.getAttribute("max-value") - v_ptos_consumidos;
    input_ptos_habilidad.value = p_ptos_habilidad.getAttribute("max-value") - v_ptos_consumidos;
}