<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DnD GM</title>
    <link rel="icon" href="./resources/shared/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="./views/index/styles/index.css">
    <script defer src="./views/index/scripts/index.js"></script>
</head>

<body>
    <h1>TFC - DnD_GM_WebManager</h1>
    <h2>Diseñado por: Iván Freire Dacoba</h2>
    <hr>
    <form action="profile" method="POST">
        <h3>Login</h3>
        <p>
            <label for="nombre">Nombre: </label>
            <input type="text" name="nombre" id="nombre" required/>
        </p>
        <p>
            <label for="password">Contraseña: </label>
            <input type="password" name="password" id="password" required/>
        </p>
        <input type="button" value="Entrar" id="logear"/>
        <button class="changeViewButton" id="swapRegister" type="button">Regístrate</button>
    </form>
    <!--Registro-->
    <form action="register" method="POST" hidden>
        <h3>Registro</h3>
        <p>
            <label for="nombre">Nombre: </label>
            <input type="text" name="nombre" id="nombreReg" required/>
        </p>
        <p>
            <label for="correo">Correo: </label>
            <input type="text" name="correo" id="correo" required/>
        </p>
        <p>
            <label for="password">Contraseña: </label>
            <input type="password" name="password" id="passwordReg" required/>
        </p>
        <p>
            <label for="password2">Repetir la contraseña: </label>
            <input type="password" name="password2" id="password2" required/>
        </p>
        <p>
            <label for="password2">Aceptar <a href="">términos de uso</a>: </label>
            <input type="checkbox" name="useTerms" id="useTerms" required/>
        </p>
        <input type="button" value="Registrarse" id="registrarse" disabled/>
        <button class="changeViewButton" type="button" id="swapLogin">¿Ya estás registrado?</button>
    </form>
</body>

</html>