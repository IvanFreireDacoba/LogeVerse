<?php
//Control de acceso solo a usuarios con la sesion iniciada
if (!isset($_SESSION["usuario"])) {
    $_SESSION["alert"] = "No tienes permiso para acceder a esta página.";
    header("Location: LogeVerse/inicio");
    exit;
}
?>
<!DOCTYPE html>

<html lang="es">

<head>
    <?php
    //Añadimos el head de la página común al resto de páginas
    include_once 'LogeVerse/views/shared/head.php';
    ?>
    <script type="module" src="/LogeVerse/views/propuestas/scripts/mainScript.js"></script>
    <link rel="stylesheet" href="/LogeVerse/views/propuestas/styles/propuestas.styles.css">
</head>

<body>
    <?php
    //Añadimos la cabecera de la página comín al resto de páginas
    // y el menú de navegación
    include_once 'LogeVerse/views/shared/header.php';
    ?>
    <main>
        <h2>Sección de propuesta</h2>
        <p>¡Aquí podrás proponer cualquier idea para el juego!</p>
        <p>Proponer:
            <select id="seleccionPropuesta">
                <option selected hidden disabled>--SELECCIÓN--</option>
                <option id="divClase">Clase</option>
                <option id="divRaza">Raza</option>
                <option id="divEfecto">Efecto</option>
                <option id="divPasiva">Pasiva</option>
                <option id="divHabilidad">Habilidad</option>
                <option id="divObjeto">Objeto</option>
                <option id="divIdioma">Idioma</option>
            </select>
        </p>
        <section id="formularios">
            <div id="Clase" class="propuesta" hidden>
                <h4>Proponer Clase</h4>
                <form action="/LogeVerse/proponer" method="POST">
                    <input id="proposal_type" name="proposal_type" value="clase" hidden>
                    <p>No disponible</p>
                </form>
            </div>
            <div id="Raza" class="propuesta" hidden>
                <h4>Proponer Raza</h4>
                <form action="/LogeVerse/proponer" method="POST">
                    <input id="proposal_type" name="proposal_type" value="raza" hidden>
                    <p>No disponible</p>
                </form>
            </div>
            <div id="Efecto" class="propuesta" hidden>
                <h4>Proponer Efecto</h4>
                <form action="/LogeVerse/proponer" method="POST">
                    <input id="proposal_type" name="proposal_type" value="efecto" hidden>
                    <div>
                        <label for="efecto_nombre">Nombre: </label>
                        <input id="efecto_nombre" name="efecto_nombre" type="text" placeholder="Nombre del efecto.">
                    </div>
                    <div>
                        <label for="efecto_descripcion">Descripción: </label>
                        <textarea id="efecto_descripcion" name="efecto_descripcion"
                            placeholder="Breve descipción del efecto."><?php echo $_SESSION["POST"]["efecto_descripcion"] ?? null ?></textarea>
                    </div>
                    <div>
                        <label for="efecto_cantidad">Cantidad: </label>
                        <input id="efecto_cantidad" name="efecto_cantidad" type="number" step="1" min="0"
                            placeholder="Cantidad asociada al efecto." value="0">
                        <details>
                            <summary>Info.</summary>
                            <p class="detailsInfo">Indica la cantidad de daño/buff/debuff que aplica el efecto, puede
                                sufrir cambios de modificadores.</p>
                        </details>
                    </div>
                    <div>
                        <label for="efecto_duracion">Duración: </label>
                        <input id="efecto_duracion" name="efecto_duracion" type="number" min="0" step="1" value="0">
                        <details>
                            <summary>Info.</summary>
                            <p class="detailsInfo">Número de turnos que dura el efecto. Los efectos instantáneos que se
                                aplican solo al momento de lanzarlos tienen el valor 0.</p>
                        </details>
                    </div>
                    <div>
                        <label for="efecto_tipo">Tipo: </label>
                        <select id="efecto_tipo" name="efecto_tipo">
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
            </div>
            <div id="Pasiva" class="propuesta" hidden>
                <h4>Proponer Pasiva</h4>
                <form action="/LogeVerse/proponer" method="POST">
                    <input id="proposal_type" name="proposal_type" value="pasiva" hidden>
                    <div>
                        <label for="pasiva_nombre">Nombre: </label>
                        <input id="pasiva_nombre" name="pasiva_nombre" type="text" placeholder="Nombre de la pasiva.">
                    </div>
                    <div>
                        <label for="pasiva_descripcion">Descripción: </label>
                        <textarea id="pasiva_descripcion" name="pasiva_descripcion" type="text"
                            placeholder="Breve descipción de la pasiva."></textarea>
                    </div>
                    <div id="checkbox_pasiva_efectos">
                        <label for="has_effects">Efectos </label>
                        <input id="has_effects" name="has_effects" type="checkbox">
                    </div>

                    <div id="pasiva_efectos_select" class="pasiva_efectos_select_style" hidden>
                        <div>
                            <p>Efectos seleccionados</p>
                            <div id="pasiva_selected_efects">
                            </div>
                        </div>
                        <div>
                            <p>Efectos disponibles</p>
                            <div id="pasiva_avaliable_efects">
                                <?php
                                try {
                                    $pdo = conectar();
                                    $stmt = $pdo->prepare("SELECT id, nombre, descripcion, cantidad, duracion, tipo FROM efecto;");
                                    $stmt->execute();
                                    $efectos = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                    foreach ($efectos as $efecto) {
                                        $salida = "<div class='pasiva_efecto' id='pasiva_efecto_" . $efecto["id"] . "'>
                                                    <p>" . $efecto["nombre"] . ": [duración: ";
                                        $salida .= $efecto["duracion"] === 0 ? "Instanáneo" : $efecto["duracion"];
                                        $salida .= " || cantidad : " . $efecto["cantidad"] . "]</p>
                                                <p>" . $efecto["descripcion"] . "</p>
                                                <input type='number' name='pasiva_efecto_" . $efecto["id"] . "' value='" . $efecto["id"] . "' hidden disabled>
                                                <div class='div_modificador' title='Modificador del efecto -> la cantidad del efecto se multiplicará por este modificador.' hidden>
                                                    <label for='mod_pasiva_efecto_" . $efecto["id"] . "'>Mod. </label>
                                                    <input class='modificador' id='mod_pasiva_efecto_" . $efecto["id"] . "' name='mod_pasiva_efecto_" . $efecto["id"] . "' type='number' min='1' value='1' hidden disabled>
                                                </div>
                                               </div>";
                                        echo $salida;
                                    }
                                } catch (Error $e) {
                                    echo "<p>Error al conectar con la base de datos, por favor refresca la página.</p>";
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <button class="btn_proponer" type="submit">PROPONER</button>
                    <button class="btn_reset" type="reset">Limpiar formulario</button>
                </form>
            </div>
            <div id="Habilidad" class="propuesta" hidden>
                <h4>Proponer Habilidad</h4>
                <form action="/LogeVerse/proponer" method="POST">
                    <input id="proposal_type" name="proposal_type" value="habilidad" hidden>
                    <p>No disponible</p>
                </form>
            </div>
            <div id="Objeto" class="propuesta" hidden>
                <h4>Proponer Objeto</h4>
                <form action="/LogeVerse/proponer" method="POST">
                    <input id="proposal_type" name="proposal_type" value="objeto" hidden>
                    <p>No disponible</p>
                </form>
            </div>
            <div id="Idioma" class="propuesta" hidden>
                <h4>Proponer Idioma</h4>
                <form action="/LogeVerse/proponer" method="POST">
                    <input id="proposal_type" name="proposal_type" value="idioma" hidden>
                    <div>
                        <label for="idioma_nombre">Nombre: </label>
                        <input id="idioma_nombre" name="idioma_nombre" type="text" placeholder="Nombre del idioma.">
                    </div>
                    <div>
                        <label for="idioma_descripcion">Descripción: </label>
                        <textarea id="idioma_descripcion" name="idioma_descripcion" type="text"
                            placeholder="Breve descipción del idioma."></textarea>
                    </div>
                </form>
            </div>
        </section>
    </main>
    <?php
    //Añadimos el pie de página común al resto de páginas
    include 'LogeVerse/views/shared/footer.html';
    ?>
</body>

</html>