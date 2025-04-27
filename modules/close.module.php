<?php

        include_once "../classes/include_classes.php";

        session_start();
        $_SESSION = [];
        session_destroy();

        header("Location: ../controllers/index.controller.php");