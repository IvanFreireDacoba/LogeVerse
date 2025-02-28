class chatGlobal extends HTMLElement{
    constructor() {
        super();

        this.setAttribute("id", "chatGlobal-customElement");
        this.shadow = this.attachShadow({ mode: "open" });

        this.link = document.createElement("link");
        this.link.setAttribute("rel", "stylesheet");
        this.link.setAttribute("href", "./styles/chatCustom.css");

        this.base = document.createElement("section");
        let chat = document.createElement("div");
        let datos = document.createElement("div");
        let input = document.createElement("input");
        let boton = document.createElement("button");

        boton.addEventListener("click", async () => {
            //requestDnDInfo(input.value);
            if (input.value) {
                this.enviarMensaje(await this.evaluarMensaje(input.value));
            }
        })

        input.addEventListener("keypress", (e) => {
            if (e.key == "Enter") {
                boton.dispatchEvent(new Event("click"));
            }
        })

        datos.setAttribute("id", "datosChat");
        datos.appendChild(input);
        datos.appendChild(boton);

        chat.setAttribute("id", "chat-cge");
        input.setAttribute("id", "input-cge");
        boton.setAttribute("id", "inputButton-cge");

        this.base.appendChild(chat);
        this.base.appendChild(datos);
    }

    connectedCallback() {
        this.shadow.innerHTML = `
        <style>
            section {
                display: flex;
                flex-direction: column;
                align-content: end;
                width: min-content;
                aspect-ratio: 1 / 3;
                border: 1px black solid;
                bottom: 0;

                & > #chat-cge {
                    flex-grow: 1;
                    height: 100%;
                    min-height: 0;
                    align-content: end;
                    overflow: hidden;
                    overflow-y: auto;
                }
                
                & > #datosChat{
                    display: flex;
                    bottom: 0px;
                    
                    & > #inputButton-cge {
                        background-color: green;
                    }
                }
            }
        </style>
        `;

        this.shadow.appendChild(this.base);
        this.shadow.appendChild(this.link);
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
            input.value = "";
            input.focus();
        }
    }
}

//Cargar el chat
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