<?php

        include_once "LogeVerse/classes/include_classes.php";

        session_start();
        $_SESSION = [];
        session_destroy();
        
        header("Location: /LogeVerse/inicio");
        exit;