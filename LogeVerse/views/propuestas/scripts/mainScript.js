//Importar el script de visualización de formularios
import { visibility } from './visibility.js';

//Importar el script de control de drag&drop de la imagen
import { loadedDomImg } from './imageDrag.js';

//Importar el script de pasiva_efecto
import { pasiva_efecto } from './pasiva_efecto.js';

//Importar el script de habilidad_efecto
import { habilidad_efecto } from './habilidad_efecto.js';

//Importar el script para refrescar los atributos
import { refreshAttributes } from './atributo_raza.js';

//Importar el script de control de pasivas de raza
import { controlarPasivasRaza } from './pasiva_raza.js';

//Lanzar los scripts al cargar el DOM
document.addEventListener("DOMContentLoaded", visibility);
document.addEventListener("DOMContentLoaded", loadedDomImg);
document.addEventListener("DOMContentLoaded", pasiva_efecto);
document.addEventListener("DOMContentLoaded", habilidad_efecto);
document.addEventListener("DOMContentLoaded", refreshAttributes);
document.addEventListener("DOMContentLoaded", controlarPasivasRaza);
