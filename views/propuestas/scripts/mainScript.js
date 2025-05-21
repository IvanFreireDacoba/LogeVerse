//Importar el script de visualización de formularios
import { visibility } from './visibility.js';

//Importar el script de pasiva_efecto
import { pasiva_efecto } from './pasiva_efecto.js';

//Lanzar los scripts al cargar el DOM
document.addEventListener("DOMContentLoaded", visibility);
document.addEventListener("DOMContentLoaded", pasiva_efecto);
