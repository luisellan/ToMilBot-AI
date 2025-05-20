<?php
    require_once("ClassUsuario.php");
    $objUsuario = new Usuario("Andres Cardona","andres@info.com","Admin");
    $objUsuario1 = new Usuario("Andresa","andresa@info.com","Cliente");
    echo Usuario::$strEstado;


    echo $objUsuario->gerPerfil();
    echo $objUsuario1->gerPerfil();
    echo $objUsuario1->setCambiarClave("123456");

    echo $objUsuario1->gerPerfil();

?>