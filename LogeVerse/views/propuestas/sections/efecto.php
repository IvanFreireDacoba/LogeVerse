<?php
//Control de acceso de seguridad
if (!defined('IN_CONTROLLER')) {
    $_SESSION["Alert"] = "Acceso directo no permitido.";
    header("/LogeVerse/inicio");
    exit;
}
?>
<section id="Efecto" class="propuesta" hidden>
    <h4>Proponer Efecto</h4>
    <form action="/LogeVerse/proponer" method="POST">
        <input id="proposal_type" name="proposal_type" value="efecto" hidden required>
        <div>
            <label for="efecto_nombre">Nombre: </label>
            <input id="efecto_nombre" name="efecto_nombre" type="text" placeholder="Nombre del efecto." required>
        </div>
        <div>
            <label for="efecto_descripcion">Descripción: </label>
            <textarea id="efecto_descripcion" name="efecto_descripcion" placeholder="Breve descipción del efecto."
                required><?php echo $_SESSION["POST"]["efecto_descripcion"] ?? null ?>></textarea>
        </div>
        <div>
            <label for="efecto_cantidad">Cantidad: </label>
            <input id="efecto_cantidad" name="efecto_cantidad" type="number" step="1" min="0"
                placeholder="Cantidad asociada al efecto." value="0" required>
            <details>
                <summary>Info.</summary>
                <p class="detailsInfo">Indica la cantidad de daño/buff/debuff que aplica el efecto, puede
                    sufrir cambios de modificadores.</p>
            </details>
        </div>
        <div>
            <label for="efecto_duracion">Duración: </label>
            <input id="efecto_duracion" name="efecto_duracion" type="number" min="0" step="1" value="0" required>
            <details>
                <summary>Info.</summary>
                <p class="detailsInfo">Número de turnos que dura el efecto. Los efectos instantáneos que se
                    aplican solo al momento de lanzarlos tienen el valor 0.</p>
            </details>
        </div>
        <div>
            <label for="efecto_tipo">Tipo: </label>
            <select id="efecto_tipo" name="efecto_tipo" required>
                <option title="Aplica daño plano." value="damage">Daño</option>
                <option title="Mejora temporalmente atributos o habilidades." value="buff">Buff</option>
                <option title="Disminuye temporalmente atributos o habilidades." value="debuff">Debuff
                </option>
                <option title="Aplica un estado: quemadura, envenenamiento, confusiónLogeVerse." value="status">
                    Estado</option>
                <option title="Efecto especial que no se cataloga en el resto de tipos." value="others">Otro
                </option>
            </select>
        </div>
        <button class="btn_proponer" type="submit">PROPONER</button>
        <button class="btn_reset" type="reset">Limpiar formulario</button>
    </form>
</section>