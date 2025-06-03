export function checkRegister() {

    let username_input = document.getElementById("username");
    let password_input = document.getElementById("password");
    let btn_login = document.getElementById("btn_login");

    //Checkeamos que el usuario no esté vacío
    function checkUsername() {
        username_input.value = username_input.value.trim();
        return username_input.value.length > 0;
    }

    //Checkeamos que la contraseña no esté vacía
    function checkPassword() {
        password_input.value = password_input.value.trim();
        return password_input.value.length > 0;
    }

    //Cuando ambos campos se rellenan, permitimos enviar el forumlario
    function checkData() {
        if (checkUsername() && checkPassword()) {
            btn_login.removeAttribute("disabled");
        } else {
            btn_login.setAttribute("disabled", "");
        }
    }
    username_input.addEventListener("input", () => {
        checkData()
    })
    password_input.addEventListener("input", () => {
        checkData()
    })

    //Comprobamos que los campos tienen datos
    btn_login.addEventListener("click", (event) => {

        //Si no tienen datos mostrmos una alerta
        //detenemos el evento "click" y ponemos el
        //foco sobre el input pertinente
        if (!checkUsername()) {
            event.preventDefault();
            alert("Nombre de usuario o correo obligatorio.");
            username_input.focus;
            return;
        }

        if (!checkPassword) {
            event.preventDefault();
            alert("Contraseña obligatoria.");
            password_input.focus;
            return;
        }

    });

    document.removeEventListener("DOMContentLoaded", checkRegister);
}