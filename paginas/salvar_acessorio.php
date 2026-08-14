<?php
require_once "../config/protege.php";
require_once "../config/conexao.php";

$nome = trim($_POST["nome"] ?? "");
$descricao = trim($_POST["descricao"] ?? "");

if ($nome === "") {
    header("Location: cadastrar_acessorio.php?erro=nome");
    exit;
}

$sql = "
    INSERT INTO acessorios (
        nome,
        descricao
    )
    VALUES (?, ?)
";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "ss",
    $nome,
    $descricao
);

if ($stmt->execute()) {

    header("Location: acessorios.php?sucesso=cadastrado");
    exit;

}

echo "Erro ao cadastrar acessório: " . $stmt->error;