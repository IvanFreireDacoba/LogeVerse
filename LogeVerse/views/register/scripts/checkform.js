export function checkRegister() {

    let username_input = document.getElementById("username");
    let email_input = document.getElementById("email");
    let password_input = document.getElementById("password");
    let password_rep_input = document.getElementById("password_rep");

    function validateUsername() {
        let valid = false;
        username_input.value = username_input.value.trim();
        if (username_input.value.length <= 50 && username_input.value.length > 0) {
            valid = true;
        }
        return valid;
    }

    function validateMail() {
        let valid = false;
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        email_input.value = email_input.value.trim();
        if (email_input.value.length <= 75 && emailRegex.test(email_input.value)) {
            valid = true;
        }
        return valid;
    }

    function validatePasswords() {
        let valid = false;
        const password = password_input.value.trim();
        const passwordRep = password_rep_input.value.trim();
        if (password.length > 0 && password === passwordRep) {
            valid = true;
        }
        return valid;
    }

    document.getElementById("btn_submit").addEventListener("click", (event) => {
        const validations = [
            {
                isValid: validateUsername(),
                input: username_input,
                message: "Nombre no válido."
            },
            {
                isValid: validateMail(),
                input: email_input,
                message: "Email no válido."
            },
            {
                isValid: validatePasswords(),
                input: password_input,
                message: "Las contraseñas no coinciden."
            }
        ];

        for (let validation of validations) {
            if (!validation.isValid) {
                event.preventDefault();
                alert(validation.message);
                validation.input.focus();
                return;
            }
        }

        if (!confirm("¿Confirmar tu registro y aceptar los términos y condiciones?")) {
            event.preventDefault();
        }
    });

    document.removeEventListener("DOMContentLoaded", checkRegister);
}