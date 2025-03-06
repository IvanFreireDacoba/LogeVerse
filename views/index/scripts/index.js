//listeners

//cambiar a seccion de registro
document.getElementById("swapRegister").addEventListener("click", () => {
    document.forms[0].setAttribute("hidden", "");
    document.forms[1].removeAttribute("hidden");
});

//cambiar a seccion de login
document.getElementById("swapLogin").addEventListener("click", () => {
    document.forms[0].removeAttribute("hidden");
    document.forms[1].setAttribute("hidden", "");
});

//gestionar el intento de login
document.getElementById("logear").addEventListener("click", (e) => {
    let status = [];
    status.push(checkName(document.forms[0][0].value));
    status.push(checkPassword(document.forms[0][1].value));  

    if (!checkStatus(status)) {
        e.preventDefault();
    }
})

//gestionar el intento de registro
document.getElementById("registrarse").addEventListener("click", (e) => {
    let status = [];
    status.push(checkName(document.forms[0][0].value));
    status.push(checkMail(document.forms[0][1].value));
    status.push(checkPassword(document.forms[0][2].value));
    status.push(comparePasswords(document.forms[0][2].value, document.forms[0][3].value));

    if (checkStatus(status)) {
        if (!confirm("¿Confirmar el registro?")) {
            e.preventDefault();
        }
    } else {
        e.preventDefault();
    }  
})

//permitir/negar el registro cuando el usuario acepta/rechaza los téminos y condiciones
document.getElementById("useTerms").addEventListener("change", (e) => {
    const registrarseButton = document.getElementById("registrarse");
    const useTerms = document.getElementById("useTerms");

    if (useTerms.checked) {
        registrarseButton.removeAttribute("disabled");
    } else {
        registrarseButton.setAttribute("disabled", "");
    }
})

//==========================================================================================================================================
//Funciones

//comprobar nombre
function checkName(name){
    return name.trim() != "";
}


//comprobar contraseña
function checkPassword(pwd){
    return pwd.trim() != "";
}

//comprobar mail
function checkMail(mail){
    return mail.trim() != "";
}

//comprobar que las contraseñas coinciden
function comparePasswords(pwd, pwd2){
    return pwd.value === pwd2.value;
}

//comprobar que pasamos todas las comprobaciones
function checkStatus(status){
    return status.every(estado => estado === true);
}