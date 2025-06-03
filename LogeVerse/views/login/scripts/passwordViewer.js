export function passwordViewer() {

    let password_input = document.getElementById("password");
    let password_checkbox = document.getElementById("cb_password");

    password_checkbox.addEventListener("change", () => {
        if (password_checkbox.checked) {
            password_input.setAttribute("type", "text");
        } else {
            password_input.setAttribute("type", "password");
        }
    })

    document.removeEventListener("DOMContentLoaded", passwordViewer);
}