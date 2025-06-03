export function loadedDomImg() {

    const dropArea = document.getElementById('dropArea');
    const input = document.getElementById('imagen');
    const preview = document.getElementById('preview');
    const placeholder = document.getElementById('placeholder');

    //Clic abre el input
    dropArea.addEventListener('click', () => input.click());

    //Efectos visuales al arrastrar
    ['dragenter', 'dragover'].forEach(eventName => {
        dropArea.addEventListener(eventName, e => {
            e.preventDefault();
            e.stopPropagation();
            dropArea.classList.add('hover');
        });
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropArea.addEventListener(eventName, e => {
            e.preventDefault();
            e.stopPropagation();
            dropArea.classList.remove('hover');
        });
    });

    //Soltar imagen reemplaza la anterior
    dropArea.addEventListener('drop', e => {
        const file = e.dataTransfer.files[0];
        if (file && file.type.startsWith('image/')) {
            mostrarImagen(file);
            input.files = e.dataTransfer.files; //Actualizar el input
        }
    });

    //Cambiar desde el input
    input.addEventListener('change', () => {
        const file = input.files[0];
        if (file && file.type.startsWith('image/')) {
            mostrarImagen(file);
        }
    });

    function mostrarImagen(file) {
        const reader = new FileReader();
        reader.onload = e => {
            preview.src = e.target.result;
            preview.style.display = 'block';
            placeholder.style.display = 'none';
        };
        reader.readAsDataURL(file);
    }

    //Eliminar el listener del DOMContentLoaded (JScript no gestiona esto de manera automática)
    document.removeEventListener("DOMContentLoaded", loadedDomImg);
}