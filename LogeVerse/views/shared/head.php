<?php
//Control de acceso de seguridad
if (!defined('IN_CONTROLLER')) {
    $_SESSION["Alert"] = "Acceso directo no permitido.";
    header("/LogeVerse/inicio");
    exit;
}
//Si el usuario tiene alguna alerta pendiente, la mostramos
if (isset($_SESSION["alert"])) {
    echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                alert(" . json_encode($_SESSION["alert"]) . ");
            });
          </script>";
    //Una vez mostrada la alerta, la eliminamos de la sesión
    unset($_SESSION["alert"]);
}
?>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>
    <?php
    echo $title ?? "ERROR_001";
    ?>
</title>
<link rel="icon" href="/LogeVerse/resources/shared/favicon.png" type="image/png">
<!-- Fuentes -->
<link href="https://fonts.googleapis.com/css2?family=Bona+Nova+SC&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">
<link href="https://api.fontshare.com/v2/css?f[]=bonny@400&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Jaro&display=swap" rel="stylesheet">
<!-- Estilos -->
<link rel="stylesheet" href="/LogeVerse/views/shared/styles/general.css">
<link rel="stylesheet" href="/LogeVerse/views/shared/styles/header.css">
<link rel="stylesheet" href="/LogeVerse/views/shared/styles/footer.css">