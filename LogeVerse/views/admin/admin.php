<?php
//Control de acceso de seguridad
if (!defined('IN_CONTROLLER')) {
    $_SESSION["Alert"] = "Acceso directo no permitido.";
    header("/LogeVerse/inicio");
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
    <link rel="stylesheet" href="/LogeVerse/views/admin/styles/admin.css" />
    <script type="module" src="/LogeVerse/views/admin/scripts/mainScript.js"></script>
</head>

<body>
    <?php
    //Añadimos la cabecera de la página comín al resto de páginas
    // y el menú de navegación
    include_once 'LogeVerse/views/shared/header.php';
    ?>
    <h1>Portal de Administración</h1>
    <div class="double_grid_column">
        <form action="/LogeVerse/portalAdmin" method="POST">
            <label for="admin_section">Viaje rápido: </label>
            <select id="sel_selector" name="admin_section">
                <?php
                echo "<option value='" . $section . "'>" . $section_name . "</option>";
                foreach ($nombre_propuestas as $key => $value) {
                    if ($key != $section) {
                        echo "<option value='" . $key . "'>" . $value . "</option>";
                    }
                }
                ?>
            </select>
        </form>
    </div>
    <br>
    <main>
        <div id="cabecera_prop">
            <form class="toRigth" action="/LogeVerse/portalAdmin" method="POST">
                <input name="admin_section" value=<?php echo $section == 0 ? (count($nombre_propuestas) - 1) : '"' . ($section - 1) . '"'; ?> hidden>
                <button type="submit">PREV</button>
            </form>
            <h4 id="cabecera_centro"><?php echo $section_name ?></h4>
            <form class="toLeft" action="/LogeVerse/portalAdmin" method="POST">
                <input name="admin_section" value=<?php echo $section == (count($nombre_propuestas) - 1) ? "0" : '"' . ($section + 1) . '"'; ?> hidden>
                <button type="submit">NEXT</button>
            </form>
        </div>
        <?php
        $section_name = strtolower($section_name);
        switch ($section) {
            case 0:
            case 1:
            case 2:
            case 3:
            case 4:
            case 5:
            case 6: {
                include "LogeVerse/views/admin/sections/{$section_name}.php";
                break;
            }
            default: {
                echo "<p>Error desconocdido<p>";
                break;
            }
        }
        ?>
    </main>
</body>

</html>