<?php

$host = "localhost";
$usuario = "SEU_USUARIO";
$senha = "SUA_SENHA";
$banco = "inventario_clinico";

$conn = new mysqli($host, $usuario, $senha, $banco);

if ($conn->connect_error) {
    die("Não foi possível conectar ao banco de dados.");
}

$conn->set_charset("utf8mb4");
?>