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
            if (input.value) {
                //Funciones para enviar un mensaje y evaluarlo usando APIs si es necesario
                this.enviarMensaje(await this.evaluarMensaje(input.value.trim()));
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
                case "ON": {
                    document.getElementById("chatGlobal-customElement").style = /*css*/ `
                        position: fixed; top: 12dvh; animation: cge-ocultar 1.5s 1 forwards;
                    `;
                    chatControllButton.innerHTML = "OFF";
                    botonControlador.classList.add("chatOFF");
                    botonControlador.classList.remove("chatON");
                    break;
                }
                case "OFF": {
                    document.getElementById("chatGlobal-customElement").style = /*css*/ `
                        position: fixed; top: 12dvh; animation: cge-mostrar 1.5s 1 forwards;
                    `;
                    chatControllButton.innerHTML = "ON";
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
            let parts = mns.trim().substring(1).split(" ");
            let cmd = parts[0].toLowerCase() || "";
            let data = parts.slice(1).join(" ") || "";
            switch (cmd) {
                case "raza": {
                    mns = await buscar("races", data)
                        .then(async (response) => {
                            let name = await traducir(response.name);
                            let desc = await traducir(response.size_description);
                            return `<p class="info">
                                        <a id="responseTitle">${name}</a>:
                                        <br>
                                        <a id="responseDatum">${desc}</a>
                                    </p>`;
                        })
                        .catch(() => {
                            return "<p class='info'>No se encontró la clase.</p>";
                        });
                    break;
                }
                case "rasgo": {
                    mns = await buscar("traits", data)
                        .then(async (response) => {
                            let name = await traducir(response.name);
                            let desc = await traducir(response.desc);
                            return `<p class="info">
                                        <a id="responseTitle">${name}</a>:
                                        <br>
                                        <a id="responseDatum">${desc}</a>
                                    </p>`;
                        })
                        .catch(() => {
                            return "<p class='info'>No se encontró el rasgo.</p>";
                        });
                    break;
                }
                default: {
                    mns = "<p class='info'>Comando no reconocido.</p>";
                }
            }
        } else {
            mns = "<p class='mns'>" + mns + "</p>";
        }
        return mns;
    }

    //Enviar un mensaje "mns", no puede estar vacío o ser un conjunto de espacios
    enviarMensaje(mns) {
        if(mns){
            let chat = document.getElementById("chatGlobal-customElement").shadow.querySelector("#chat-cge");
            chat.innerHTML += "<br>" + mns;
            let input = document.getElementById("chatGlobal-customElement").shadow.querySelector("#input-cge");
            chat.scrollTop = chat.scrollHeight;
            input.value = "";
            input.focus();
        }
    }
}

//Cargar el chat ¡OJO! No funciona si el script se carga con "defer"!
addEventListener("DOMContentLoaded", () => {
    if(document.body){
        console.log("Chat Cargado");
        document.body.appendChild(document.createElement("chat-global"));
        customElements.define("chat-global", chatGlobal);
    } else {
        console.error("No se ha podido inicializar el chat");
    }
})

//FUNCIONES DE APIS

//Buscar información de DnD => ES UNA API GRATUITA QUE NO REQUIERE DE API-KEY
async function buscar(url, query) {
    const myHeaders = new Headers();
    myHeaders.append("Accept", "application/json");
    const requestOptions = {
        method: "GET",
        headers: myHeaders,
        redirect: "follow"
    };
    return await fetch(`https://www.dnd5eapi.co/api/${url}/${query}`, requestOptions)
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

//Traducir un mensaje (por defecto, al español) => SE DEBE USAR EL OAUTH EN UN FUTURO
async function traducir(mns, lang = "es", sourceLang = "en") {
    const baseUrl = "https://lingva.ml/api/v1";
    const url = `${baseUrl}/${sourceLang}/${lang}/${encodeURIComponent(mns)}`;

    try {
        const response = await fetch(url);
        if (!response.ok) {
            throw new Error(`Error HTTP: ${response.status}`);
        }
        const data = await response.json();
        return data.translation;
    } catch (error) {
        console.error("Error al traducir:", error);
        return null;
    }
}