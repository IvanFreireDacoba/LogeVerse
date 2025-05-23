<?php
    //Iniciamos la sesión (siempre la mentenemos en cada página)
    include_once '../classes/include_classes.php';
    $_SESSION ?? session_start();

    //Si el usuario tiene alguna alerta pendiente, la mostramos
    if(isset($_SESSION["alert"])){
        echo "<script>
                window.onload = function() {
                    alert('" . $_SESSION["alert"] . "');
                };
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
<link rel="icon" href="../resources/shared/favicon.png" type="image/png">