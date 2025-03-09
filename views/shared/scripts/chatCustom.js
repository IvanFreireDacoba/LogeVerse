//LOS DEBUGS SE PODRÁN CONTROLAR DESDE UNA COOKIE PARA ADMINISTRADORES, POR DEFECTO ESTARÁN DESACTIVADOS

class chatGlobal extends HTMLElement{
    constructor() {
        super();
        /*Por defecto el chat aparece con un estilo oculto
        Lo controlo desde aquí ya que el css externo solo afecta al contenido del shadow,
        además, no quiero tener que añadir NADA más, ni siquiera un estilo, para controlar
        el chat, TODO debe generarse de manera automática tan solo con cargar el script
        */ 
        this.style = "position: fixed; right: -195px; top: 12dvh;"
        //Le pongo un id para localizarlo fácilmente
        this.setAttribute("id", "chatGlobal-customElement");
        //Usamos el shadow
        this.shadow = this.attachShadow({ mode: "open" });
        //establecemos una hoja de estilos externa
        this.link = document.createElement("link");
        this.link.setAttribute("rel", "stylesheet");
        this.link.setAttribute("href", "./styles/chatCustom.css");
        //La base será un divisor que contenga el resto de elementos
        this.base = document.createElement("div");
        this.base.setAttribute("id", "chat-base")
        //Sección para el chat, en ella se verán los mensajes y el input
        let section = document.createElement("section");
        section.setAttribute("id", "seccionInChat");
        //Zonas de la sección:
        //Contiene todo el "chat"
        let chat = document.createElement("div");
        chat.setAttribute("id", "chat-cge");
        //Datos del chat (mensajes)
        let datos = document.createElement("div");
        datos.setAttribute("id", "datosChat");
        //Input y botón para poder enviar los mensajes
        let input = document.createElement("input");
        input.setAttribute("id", "input-cge");
        let boton = document.createElement("button");
        boton.setAttribute("id", "inputButton-cge");
        //Estructura de cada zona
        //En el divisor de datos se gestionan el envío de datos
        datos.appendChild(input);
        datos.appendChild(boton);
        //La seccion del chat estará compuesta por el chat (mesnajes) y el sistema de envío (datos)
        section.appendChild(chat);
        section.appendChild(datos);
        //Añadir el botón de control, con él podremos establecer si el chat está visible o no
        //Un contenedor para todo el botón
        let botonControlador = document.createElement("div");
        botonControlador.setAttribute("id", "chatButtonContainer");
        botonControlador.textContent = "Chat";
        //Un área sobre el que se moverá el botón, esta cambiará de color
        let chatButtonArea = document.createElement("div");
        chatButtonArea.classList.add("chatButtonArea");
        //El botón en sí
        let chatControllButton = document.createElement("div");
        chatControllButton.textContent = "OFF";
        chatControllButton.classList.add("chatControllButton");
        //Los anidamos ordenadamente 
        botonControlador.appendChild(chatButtonArea);
        chatButtonArea.appendChild(chatControllButton);
        /*
                    LISTENERS
        */
        //Función para enviar un mensaje al chat después de evaluarlo
        boton.addEventListener("click", async () => {
            //Si hay un mensaje en el input, lo enviamos
            if (input.value) {
                //Deshabilitamos el input para evitar enviar mensajes duplicados
                input.setAttribute("disabled", "");
                //Funciones para enviar un mensaje y evaluarlo usando APIs si es necesario
                this.enviarMensaje(await this.evaluarMensaje(input.value));
            }
        })
        //Al pulsar "Enter" en el input, se imita al evento anterior (Enviamos el mns)
        input.addEventListener("keypress", (e) => {
            if (e.key == "Enter") {
                boton.dispatchEvent(new Event("click"));
            }
        })
        //Listener del botón para mostrar/ocultar el chat (se puede pulsar en cualquier sitio del área)
        chatButtonArea.addEventListener("click", () => {
            switch (chatControllButton.innerHTML.trim()) {
                //Cuando está en "ON" se oculta
                case "ON": {
                    //Animación para ocultar el chat
                    document.getElementById("chatGlobal-customElement").style = /*css*/ `
                        position: fixed; top: 12dvh; animation: cge-ocultar 1.5s 1 forwards;
                    `;
                    //Cambiamos el texto para el siguiente estado
                    chatControllButton.innerHTML = "OFF";
                    //Cambiar los estilos del botón (utilizamos clases para ello)
                    botonControlador.classList.add("chatOFF");
                    botonControlador.classList.remove("chatON");
                    break;
                }
                //Cuando está en "OFF" se muestra
                case "OFF": {
                    //Animación para mostrar el chat
                    document.getElementById("chatGlobal-customElement").style = /*css*/ `
                        position: fixed; top: 12dvh; animation: cge-mostrar 1.5s 1 forwards;
                    `;
                    //Cambiamos el texto para el siguiente estado
                    chatControllButton.innerHTML = "ON";
                    //Cambiar los estilos del botón (utilizamos clases para ello)
                    botonControlador.classList.add("chatON");
                    botonControlador.classList.remove("chatOFF");
                    break;
                }
            }
        })
        //Preparar la base (el sistema de chat más el boton para mostrarlo/ocultarlo)
        this.base.appendChild(botonControlador);
        this.base.appendChild(section);
    }

    //Añadir los elementos al shadow desde el connectedCallback
    connectedCallback() {
        this.shadow.appendChild(this.link);
        this.shadow.appendChild(this.base);
    }

    //FUNCIONES DEL CHAT

    //Evaluar un mensaje para establecer el formato
    async evaluarMensaje(mnsRaw) {
        if (!mnsRaw) return;
        let mns = mnsRaw.trim();
        if (mns.startsWith("/")) {
            //Si empieza por "/" es un comando, lo evaluamos:
            //Lo separamos por partes tras eliminar espacios y el "/"
            let parts = mns.trim().substring(1).split(" ");
            //guardamos el comando y los datos que lo acompañan
            let cmd = parts[0].toLowerCase() || "";
            let data = parts.slice(1).join(" ") || "";
            /*
            Creo una función para ejecutar comandos y devolver el mensaje
            esto me permite añadir más comandos en el futuro sin tener que modificar
            el código de evaluarMensaje, además de un control mejor de los comandos
            */ 
            return await ejecutarComando(cmd, data);
        } else {
            //Si no es un comando, se envía tal cual
            mns = "<p class='mns'>" + mns + "</p>";
        }
        return mns;
    }

    //Enviar un mensaje "mns", no puede estar vacío o ser un conjunto de espacios
    enviarMensaje(mns) {
        let input = document.getElementById("chatGlobal-customElement").shadow.querySelector("#input-cge");
        //Si el mensaje no está vacío, lo enviamos
        if(mns.trim()){
            let chat = document.getElementById("chatGlobal-customElement").shadow.querySelector("#chat-cge");
            chat.innerHTML += "<br>" + mns;
            chat.scrollTop = chat.scrollHeight;
            /*
            Control de errores, cuando se envía un mensaje de clase "error", este NO se añade
            al chat global, sino que se muestra en el input para que el usuario pueda verlo.
            Es decir, NO quedará registrado en el chat y SOLO el usuario podría verlo hasta
            que recargue la página, entonces desaparecerá.
            */ 
            if(!String(mns).startsWith("<p class='error'")){
                //Función para enviar un mensaje al chat-global => NO SE IMPLEMENTA AÚN (WebSockets)
                //mensajeChatGlobal(mns);
                //Mensaje por consola para saber se ha enviado el mensaje al chat global -> DEBUG
                console.log("Mensaje enviado:", mns);
            }
        }
        //Volvemos a habilitar el input (deshabilitado desde el Listener del botón)
        input.removeAttribute("disabled");
        input.value = "";
        input.focus();
    }
}

/*
    Cargar el chat ¡OJO! No funciona si el script se carga con "defer"!
    Cuando el contenido del DOM se carga, creamos el chat y lo añadimos al body.
    Esto se hace para que el chat se cargue automáticamente al cargar la página sin tener
    que añadir la etiqueta <chat-global> en el HTML manualmente.
*/
addEventListener("DOMContentLoaded", () => {
    if(document.body){
        //Mensaje por consola para saber que el chat se ha cargado -> DEBUG
        console.log("Chat Cargado");
        document.body.appendChild(document.createElement("chat-global"));
        customElements.define("chat-global", chatGlobal);
    } else {
        //Mensaje de error si no se ha podido cargar el chat -> DEBUG
        console.error("No se ha podido inicializar el chat");
    }
})

//FUNCIONES DE APIS

//Buscar información de DnD => ES UNA API GRATUITA QUE NO REQUIERE DE API-KEY
async function buscar(url, query) {
    /*
        El código siguiente se ha tomado de la documentación de la API de DnD5e
        https://5e-bits.github.io/docs/api
    */
   //Se añade una isntancia myHeaders de la clase Headers para indicar que se espera un JSON mediante GET
    const myHeaders = new Headers();
    myHeaders.append("Accept", "application/json");
    const requestOptions = {
        method: "GET",
        headers: myHeaders,
        redirect: "follow"
    };
    return await fetch(`https://www.dnd5eapi.co/api/${url}/${query}`, requestOptions)
        //Se modica el código de la documentación (then y catch) acorde a las necesidades del chat
        .then((response) => {
            if (!response.ok) {
                throw new Error("No se encontró el recurso.");
            }
            return response.json();
        })
        .catch(() => {
            return;
        });
}

//Traducir un mensaje (por defecto, al español) => ES UNA API GRATUITA QUE NO REQUIERE DE API-KEY
async function traducir(mns, lang = "es", sourceLang = "en") {
    /*
        Documentación de la API de Lingva
        https://github.com/thedaviddelta/lingva-translate?tab=readme-ov-file
    */
    const baseUrl = "https://lingva.ml/api/v1";
    const url = `${baseUrl}/${sourceLang}/${lang}/${encodeURIComponent(mns)}`;

    try {
        const response = await fetch(url);
        if (!response.ok) {
            throw new Error(`Error: ${response.status}`);
        }
        const data = await response.json();
        return data.translation;
    } catch (error) {
        return null;
    }
}

//Ejecutar un comando
async function ejecutarComando(cmd, data) {
    let mns = "";
    //En función del comando, se ejecuta una acción
    switch (cmd) {
        case "raza": {
            cmd = "races";
            break;
        }
        case "rasgo": {
            cmd = "traits";
            break;
        }
        case "clase": {
            cmd = "classes";
            break;
        }
        //Comando para mostrar los comandos disponibles
        case "comandos": {
            mns = `<p class='info'>Comandos disponibles:</p>
                   <br>
                   <p class='info'>/raza &lt;nombre_raza&gt;</p>
                   <br>
                   <p class='info'>/clase &lt;nombre_clase&gt;</p>
                   <br>
                   <p class='info'>/rasgo &lt;nombre_rasgo&gt;</p>`;
            break;
        }
        //Por defecto, si no se reconoce el comando, se muestra un mensaje de error
        default: {
            cmd = "";
            mns = "<p class='error'>Comando no reconocido.</p>";
        }
        //Si el comando no está vacío y no es el de búsqueda de comandos, se busca la información
        if(cmd.trim() && cmd != "comandos"){
            mns = await buscar(cmd, data)
                .then(async (response) => {
                    //Traducir el nombre y la descripción de la raza si se encuentra información válida
                    let name = await traducir(response.name);
                    //Control para "malas traducciones" del nombre
                    name = corregirTraduccion(name);
                    let desc = await traducir(response.size_description);
                    //Se devuelve el mensaje con la información formateada
                    return `<p class="cmdInfo">
                                <a class="responseTitle">${name}</a>:
                                <br>
                                <a class="responseDatum">${desc}</a>
                            </p>`;
                })
                .catch(() => {
                    //Si no se encuentra la información, se muestra un mensaje de error acorde al comando ejecutado
                    let errorInfo = "<p class='error'>No se encontró";
                    switch (cmd) {
                        case "races": {
                            errorInfo += " la raza.</p>";
                            break;
                        }
                        case "traits": {
                            errorInfo += " el rasgo.</p>";
                            break;
                        }
                        case "classes": {
                            errorInfo += " la clase.</p>";
                            break;
                        }
                        default: {
                            errorInfo = "<p class='error'>Se ha producido un error desconocido.</p>";
                        }
                    }
                    return errorInfo;
                });
        }
        
    }
    return mns;
}

//Función para corregir errores de traducción
/*
    Desde aquí controlaré las traducciones que no sean "correctas"
    o que no se ajusten a lo que espero de ellas.
*/
function corregirTraduccion(name) {
    //Lista de errores encontrados durante las pruebas. [Sujeto a cambios]
    switch (name) {
        case "Duende": {
            name = "Elfo";
        }
    }
    return name;
}