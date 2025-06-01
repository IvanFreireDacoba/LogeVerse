//Importar el script de control de registro
import { checkRegister } from './checkform.js';

//Importar el script de visualización de constraseñas
import { passwordViewer } from './passwordViewer.js';

//Lanzar los scripts al cargar el DOM
document.addEventListener("DOMContentLoaded", checkRegister);
document.addEventListener("DOMContentLoaded", passwordViewer);