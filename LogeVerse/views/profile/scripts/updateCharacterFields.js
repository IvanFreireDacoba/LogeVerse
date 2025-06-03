export function updateCharacterFields() {

    //Informar de los campos de los que se solcita la update
    let historia_update = document.getElementById('update_historia');

    //Campos que se pueden actualizar
    let input_historia = document.getElementById('historia_input');

    document.removeEventListener('DOMContentLoaded', updateCharacterFields);
}