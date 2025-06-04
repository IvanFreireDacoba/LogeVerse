<?php
//Control de acceso de seguridad
if (!defined('IN_CONTROLLER')) {
    $_SESSION["Alert"] = "Acceso directo no permitido.";
    header("Location: /LogeVerse/inicio");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <?php
    //Incluir el head común (meta, title)
    include 'LogeVerse/views/shared/head.php';
    ?>
    <link rel="icon" href="/LogeVerse/resources/shared/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="/LogeVerse/views/index/styles/index.css">
</head>

<body>
    <?php
    //Incluir el header común (nav)
    include 'LogeVerse/views/shared/header.php';
    ?>
    <h1>LogeVerse</h1>
    <h2>SUND</h2>
    <hr>
    <main>
        <div id="destacados">
            <h2>Personajes destacados</h2>
            <section id="pjsSlideShow">
                <div id="divMove">
                    <div id="fichasDiv">
                        <div class="fichaPersonaje">
                            <div class="personaje"><img class="pj_img" src="/LogeVerse/resources/index/pj1.png" ;
                                    alt="Imagen del personaje">
                                <div class="pj_data">
                                    <p class="name_lvl"><a class="pj_name">Yveria</a><a class="pj_lvl">Nv.255</a></p>
                                    <p><a class="pj_race">Elfo</a> <a class="pj_class">Sanador</a></p>
                                </div>
                            </div>
                        </div>
                        <div class="fichaPersonaje">
                            <div class="personaje"><img class="pj_img" src="/LogeVerse/resources/index/pj2.png" ;
                                    alt="Imagen del personaje">
                                <div class="pj_data">
                                    <p class="name_lvl"><a class="pj_name">Zyrion</a><a class="pj_lvl">Nv.180</a></p>
                                    <p><a class="pj_race">Dracónido</a> <a class="pj_class">Guerrero</a></p>
                                </div>
                            </div>
                        </div>
                        <div class="fichaPersonaje">
                            <div class="personaje"><img class="pj_img" src="/LogeVerse/resources/index/pj3.png" ;
                                    alt="Imagen del personaje">
                                <div class="pj_data">
                                    <p class="name_lvl"><a class="pj_name">Driux</a><a class="pj_lvl">Nv.420</a></p>
                                    <p><a class="pj_race">Gnomo</a> <a class="pj_class">Pícaro</a></p>
                                </div>
                            </div>
                        </div>
                        <div class="fichaPersonaje">
                            <div class="personaje"><img class="pj_img" src="/LogeVerse/resources/index/pj4.png" ;
                                    alt="Imagen del personaje">
                                <div class="pj_data">
                                    <p class="name_lvl"><a class="pj_name">Anarquero</a><a class="pj_lvl">Nv.60</a></p>
                                    <p><a class="pj_race">Humano</a> <a class="pj_class">Arquero</a></p>
                                </div>
                            </div>
                        </div>
                        <div class="fichaPersonaje">
                            <div class="personaje"><img class="pj_img" src="/LogeVerse/resources/index/pj5.png" ;
                                    alt="Imagen del personaje">
                                <div class="pj_data">
                                    <p class="name_lvl"><a class="pj_name">Dev Il</a><a class="pj_lvl">Nv.153</a></p>
                                    <p><a class="pj_race">Tiefling</a> <a class="pj_class">Mago</a></p>
                                </div>
                            </div>
                        </div>
                        <div class="fichaPersonaje">
                            <div class="personaje"><img class="pj_img" src="/LogeVerse/resources/index/pj6.png" ;
                                    alt="Imagen del personaje">
                                <div class="pj_data">
                                    <p class="name_lvl"><a class="pj_name">Juan K.<br>Tana</a><a
                                            class="pj_lvl">Nv.18</a>
                                    </p>
                                    <p><a class="pj_race">Humano</a> <a class="pj_class">Guerrero</a></p>
                                </div>
                            </div>
                        </div>
                        <div class="fichaPersonaje">
                            <div class="personaje"><img class="pj_img" src="/LogeVerse/resources/index/pj1.png" ;
                                    alt="Imagen del personaje">
                                <div class="pj_data">
                                    <p class="name_lvl"><a class="pj_name">Yveria</a><a class="pj_lvl">Nv.255</a></p>
                                    <p><a class="pj_race">Elfo</a> <a class="pj_class">Sanador</a></p>
                                </div>
                            </div>
                        </div>
                        <div class="fichaPersonaje">
                            <div class="personaje"><img class="pj_img" src="/LogeVerse/resources/index/pj2.png" ;
                                    alt="Imagen del personaje">
                                <div class="pj_data">
                                    <p class="name_lvl"><a class="pj_name">Zyrion</a><a class="pj_lvl">Nv.180</a></p>
                                    <p><a class="pj_race">Dracónido</a> <a class="pj_class">Guerrero</a></p>
                                </div>
                            </div>
                        </div>
                        <div class="fichaPersonaje">
                            <div class="personaje"><img class="pj_img" src="/LogeVerse/resources/index/pj3.png" ;
                                    alt="Imagen del personaje">
                                <div class="pj_data">
                                    <p class="name_lvl"><a class="pj_name">Driux</a><a class="pj_lvl">Nv.420</a></p>
                                    <p><a class="pj_race">Gnomo</a> <a class="pj_class">Pícaro</a></p>
                                </div>
                            </div>
                        </div>
                        <div class="fichaPersonaje">
                            <div class="personaje"><img class="pj_img" src="/LogeVerse/resources/index/pj4.png" ;
                                    alt="Imagen del personaje">
                                <div class="pj_data">
                                    <p class="name_lvl"><a class="pj_name">Anarquero</a><a class="pj_lvl">Nv.60</a></p>
                                    <p><a class="pj_race">Humano</a> <a class="pj_class">Arquero</a></p>
                                </div>
                            </div>
                        </div>
                        <div class="fichaPersonaje">
                            <div class="personaje"><img class="pj_img" src="/LogeVerse/resources/index/pj5.png" ;
                                    alt="Imagen del personaje">
                                <div class="pj_data">
                                    <p class="name_lvl"><a class="pj_name">Dev Il</a><a class="pj_lvl">Nv.153</a></p>
                                    <p><a class="pj_race">Tiefling</a> <a class="pj_class">Mago</a></p>
                                </div>
                            </div>
                        </div>
                        <div class="fichaPersonaje">
                            <div class="personaje"><img class="pj_img" src="/LogeVerse/resources/index/pj6.png" ;
                                    alt="Imagen del personaje">
                                <div class="pj_data">
                                    <p class="name_lvl"><a class="pj_name">Juan K.<br>Tana</a><a
                                            class="pj_lvl">Nv.18</a>
                                    </p>
                                    <p><a class="pj_race">Humano</a> <a class="pj_class">Guerrero</a></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>
    <?php
    //Incluir el footer común (nav)
    include 'LogeVerse/views/shared/footer.html';
    ?>
</body>

</html>