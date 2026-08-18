<?php

function somenteAdmin()
{
    if (
        !isset($_SESSION["usuario_nivel"]) ||
        $_SESSION["usuario_nivel"] !== "admin"
    ) {
        header("Location: /inventario-clinico/index.php");
        exit;
    }
}

function podeGerenciarManutencao()
{
    $niveisPermitidos = [
        "admin",
        "operador_manutencao"
    ];

    if (
        !isset($_SESSION["usuario_nivel"]) ||
        !in_array(
            $_SESSION["usuario_nivel"],
            $niveisPermitidos,
            true
        )
    ) {
        header("Location: /inventario-clinico/index.php");
        exit;
    }
}
?>