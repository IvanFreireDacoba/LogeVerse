<?php
//Control de acceso de seguridad
if (!defined('IN_CONTROLLER')) {
    $_SESSION["Alert"] = "Acceso directo no permitido.";
    header("Location: " . url_init . "/LogeVerse/inicio");
    exit;
}
?>
<section id="Idioma" class="propuesta" hidden>
    <h4>Proponer Idioma</h4>
    <form action="<?php echo url_init ?>/LogeVerse/proponer" method="POST">
        <input id="proposal_type" name="proposal_type" value="idioma" hidden required>
        <div>
            <label for="idioma_nombre">Nombre: </label>
            <input id="idioma_nombre" name="idioma_nombre" type="text" placeholder="Nombre del idioma." required>
        </div>
        <div>
            <label for="idioma_descripcion">Descripción: </label>
            <textarea id="idioma_descripcion" name="idioma_descripcion" type="text"
                placeholder="Breve descipción del idioma." required></textarea>
        </div>
        <button class="btn_proponer" type="submit">PROPONER</button>
        <button class="btn_reset" type="reset">Limpiar formulario</button>
    </form>
</section>