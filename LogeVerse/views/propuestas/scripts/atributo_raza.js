export function refreshAttributes() {

    const atributos = Array.from(document.getElementById('atr_raza_tbody').querySelectorAll('input'));

    atributos.forEach(atributo => {
        atributo.addEventListener('change', () => {
            let total = document.getElementById('total_puntos_raza');
            total.textContent = 0;
            atributos.forEach(atributo => {
                total.textContent = Number(total.textContent) + Number(atributo.value);
            });
        });
    })

    document.removeEventListener("DOMContentLoaded", refreshAttributes);
};