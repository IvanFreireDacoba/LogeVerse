//Importar el script de atributos
//OJO! Importante que sea el primero ya que contiene setAttributes() y updateAttributtes()
import { loadedDomAt } from './atributeSelector.js';

//Importar el script de razas
import { loadedDomRz } from './raceSelector.js';

//Importar el script de clases
import { loadedDomCl } from './classSelector.js';

//Importar el script de control de drag&drop de la imagen
import { loadedDomImg } from './imageDrag.js';

//Importar el script de randomización del personaje
import { randomizador } from './randomizer.js';

//Lanzar los scripts al cargar el DOM
document.addEventListener("DOMContentLoaded", loadedDomRz);
document.addEventListener("DOMContentLoaded", loadedDomCl);
document.addEventListener("DOMContentLoaded", loadedDomAt);
document.addEventListener("DOMContentLoaded", loadedDomImg);
document.addEventListener("DOMContentLoaded", randomizador);

