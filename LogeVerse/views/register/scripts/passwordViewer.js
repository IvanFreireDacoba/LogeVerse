export function passwordViewer() {

    let password_input = document.getElementById("password");
    let password_rep_input = document.getElementById("password_rep");
    let div_pwd_rep = document.getElementById("div_password_rep");
    let password_checkbox = document.getElementById("cb_password")

    //Comprobar que el estado del checkbox ha cambiado
    password_checkbox.addEventListener("change", () => {
        if (password_checkbox.checked) {
            //Si está seleccionado
            //Ocultamos el divisor de repetir contraseña
            div_pwd_rep.setAttribute("hidden", "");
            //Establecemos el tipo del input de contraseña en "text" provocando que se muestre
            password_input.setAttribute("type", "text");
        } else {
            //Si no está seleccionado
            //Mostramos el divisor de repetir contraseña
            div_pwd_rep.removeAttribute("hidden");
            //Ocultamos la contraseña en el input devolviéndole su tipo
            password_input.setAttribute("type", "password");
        }
    })

    //Si el checkbox está seleccionado utiliza
    password_input.addEventListener("input", () => {
        //De esta manera nos aseguramos de que las contraseñas coinciden
        if (password_checkbox.checked) {
            password_rep_input.value = password_input.value;
        }
    })

    document.removeEventListener("DOMContentLoaded", passwordViewer);
}