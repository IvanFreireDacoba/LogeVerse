export function loadedDomImg() {
    const formImages = document.querySelectorAll('.charFormImage');

    formImages.forEach(formImage => {
        const dropArea = formImage.querySelector('.drop-area');
        const input = formImage.querySelector('.imagen_form');
        const preview = formImage.querySelector('.preview');
        const placeholder = formImage.querySelector('.placeholder-text');
        const resetBtn = formImage.querySelector('.resetImage');

        //Clic en área para abrir el input
        dropArea.addEventListener('click', () => input.click());

        //Efectos al arrastrar
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

        //Imagen al soltar
        dropArea.addEventListener('drop', e => {
            const file = e.dataTransfer.files[0];
            if (file && file.type.startsWith('image/')) {
                mostrarImagen(file);
                //Actualizar el input
                input.files = e.dataTransfer.files;
            }
        });

        // magen al seleccionar input
        input.addEventListener('change', () => {
            const file = input.files[0];
            if (file && file.type.startsWith('image/')) {
                mostrarImagen(file);
            }
        });

        //Eliminar imagen
        resetBtn.addEventListener('click', e => {
            e.preventDefault();
            input.value = '';
            preview.src = '';
            preview.style.display = 'none';
            placeholder.style.display = '';
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
    });

    //Eliminar el listener del DOMContentLoaded (JScript no gestiona esto de manera automática)
    document.removeEventListener("DOMContentLoaded", loadedDomImg);
}