class chatGlobal extends HTMLElement{
    constructor() {
        super();
        
        this.setAttribute("id", "chatGlobal-customElement");
        this.shadow = this.attachShadow({mode: "open"});

        this.base = document.createElement("section");
        let chat = document.createElement("div");
        let datos = document.createElement("div");
        let input = document.createElement("input");
        let boton = document.createElement("button");

        boton.addEventListener("click", () => {
            if(input.value){
                this.enviarMensaje(input.value);
            }
        })

        input.addEventListener("keypress", (e) => {          
            if(e.key == "Enter"){
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
        //super.connectedCallback();
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
        `
        this.shadow.appendChild(this.base);
    }

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

customElements.define("chat-global", chatGlobal)