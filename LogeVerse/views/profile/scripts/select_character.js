export function selectCharacter() {
    
    const fichas = document.querySelectorAll(".fichaPersonaje");

    if (fichas !== null) {
        Array.from(fichas).forEach(ficha => {
            ficha.addEventListener("click", function () {
                const form = this.querySelector("form");
                if (form) {
                    form.submit();
                }
            });
        });
    }

    document.removeEventListener("DOMContentLoaded", selectCharacter);
}