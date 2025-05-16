document.addEventListener("DOMContentLoaded", dropUserConfirmation);

function dropUserConfirmation() {
    /*
        <input id="confirmation" name="confirmation" value="" hidden>
        <button id="remove_btn" type="submit">ELIMINAR CUENTA</button>
    */
    document.getElementById("remove_btn").addEventListener("click", (e) => {
        const username = document.getElementById("confirmation").getAttribute("data_username");

        const input = prompt(
            `¿Estás seguro de que quieres borrar tu cuenta?\n` +
            `Perderás todos los personajes y datos asociados.\n\n` +
            `Para confirmar, escribe tu nombre de usuario: "${username}" en mayúsculas.`
        );

        //Si se pulsa "cancelar"
        if (input === null) {
            //Cancelamos el evento click
            e.preventDefault();
            return;
        //Si se pulsa en aceptar comprobamos el valor escrito
        } else if (input.trim() !== username) {
            //Cancelamos el evento click
            e.preventDefault();
            //Notificamos del la escritura no igual
            alert("El nombre de usuario no coincide.\nEliminación de cuenta cancelada.");
            return;
        } else {
            //Si acepta, ponemos el nombre de usuario en el input oculto para la gestión
            //desde el módulo de drop_profile
            document.getElementById("confirmation").value = username;
        }
    })

    removeEventListener("DOMContentLoaded", dropUserConfirmation);
};