import { setAttributes } from './atributeSelector.js';

export function randomizador() {

    document.getElementById("randomize").addEventListener("click", function () {
        //Nombres
        const nombres = [
            "Thorgar", "Lyria", "Zaneth", "Morvyn", "Elandra",
            "Kael", "Nymera", "Draven", "Sylira", "Orin",
            "Vareth", "Serana", "Korin", "Myrrha", "Alric",
            "Tyveth", "Lunara", "Drazhar", "Celene", "Rothar",
            "Fenrick", "Isolde", "Mareth", "Vaelis", "Quorin",
            "Alyndra", "Bareth", "Sylos", "Elaris", "Kaedra",
            "Nareth", "Thalor", "Xandria", "Orrick", "Ysera",
            "Torvan", "Elira", "Jorveth", "Nymira", "Varek",
            "Melira", "Zareth", "Thandor", "Syrelle", "Kaelen",
            "Vaelra", "Orivar", "Selira", "Rhaegor", "Maelis",
            "Corthan", "Alenya", "Drogan", "Lyssia", "Feyron",
            "Nyrel", "Torvyn", "Zalira", "Arvik", "Lenira",
            "Kaldor", "Shaela", "Verun", "Myllia", "Zavros",
            "Thalindra", "Brayden", "Eshara", "Durvan", "Lirael",
            "Gorath", "Elyra", "Kharon", "Seraphine", "Varric",
            "Aenara", "Zarik", "Sylwen", "Borric", "Talyra",
            "Narethor", "Lazira", "Jareth", "Nyala", "Dorrin",
            "Vaesha", "Kelric", "Alindra", "Theron", "Illyra",
            "Garrik", "Yllara", "Vorin", "Calyra", "Rurik",
            "Thalara", "Jorin", "Esmerra", "Mavros", "Kaerlyn"
        ];

        //Randomizar el nombre
        const nombreInput = document.getElementById("nombre");
        nombreInput.value = nombres[Math.floor(Math.random() * nombres.length)];

        //Randomizar la raza
        const razaInput = document.getElementById("estadoRazas");
        const maxRaza = parseInt(razaInput.dataset.max);
        const randRaza = Math.floor(Math.random() * maxRaza) + 1;
        razaInput.value = randRaza;
        document.querySelectorAll(".razaContainer").forEach(div => div.hidden = true);
        const razaDiv = document.getElementById("raza_" + randRaza);
        if (razaDiv) razaDiv.hidden = false;
        setAttributes(randRaza);

        //Randomizar la clase
        const claseInput = document.getElementById("estadoClases");
        const maxClase = parseInt(claseInput.dataset.max);
        const randClase = Math.floor(Math.random() * maxClase) + 1;
        claseInput.value = randClase;
        document.querySelectorAll(".claseContainer").forEach(div => div.hidden = true);
        const claseDiv = document.getElementById("clase_" + randClase);
        if (claseDiv) claseDiv.hidden = false;

        //Preparamos los datos para el prompt de la historia
        const nombre = nombreInput.value;
        const raza = razaDiv ? razaDiv.querySelector("p.nombreRaza")?.textContent.trim() : "desconocida";
        const clase = claseDiv ? claseDiv.querySelector("p.nombreClase")?.textContent.trim() : "desconocida";
        //Obtenemos la lista de todas las razas
        const razaElems = document.querySelectorAll(".razaContainer p.nombreRaza");
        const razas = Array.from(razaElems).map(el => el.textContent.trim());
        //Construimos el prompt para la API
        const prompt = `Genera una descripción física e historia de fantasía en un universo estilo DnD llamado LogeVerse para el personaje:
                        Nombre: ${nombre}
                        Raza: ${raza}
                        Clase: ${clase}
                        Razas existentes en LogeVerse: ${razas.join(", ")}`;
        //Mostramos un texto temporal mientras la API responde
        const historiaTextarea = document.getElementById("historia");
        historiaTextarea.value = "Generando historia, espera por favor...";

        //Randomizar la historia
        randomizarHistoria();

        async function randomizarHistoria() {
            try {
                const response = await fetch("https://Logecraft.com/APIs/historias", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        prompt: prompt,
                        max_new_tokens: 150,
                        model: "gpt-3.5-turbo"
                    })
                });

                if (!response.ok) {
                    throw new Error(`Error en la API: ${response.status} ${response.statusText}`);
                }

                const data = await response.json();

                if (Array.isArray(data) && data.length > 0 && data[0].generated_text) {
                    historiaTextarea.value = data[0].generated_text.trim();
                } else {
                    historiaTextarea.value = "No se pudo generar la historia, respuesta inesperada.";
                }

            } catch (error) {
                historiaTextarea.value = "Error al generar la historia: " + error.message;
            }
        }
    });

    document.removeEventListener("DOMContentLoaded", randomizador);
}