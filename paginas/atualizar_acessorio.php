<?php
require_once "../config/protege.php";
require_once "../config/conexao.php";

$id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);
$nome = trim($_POST["nome"] ?? "");
$descricao = trim($_POST["descricao"] ?? "");

if (!$id || $nome === "") {
    header("Location: acessorios.php?erro=nao_encontrado");
    exit;
}

$sql = "
    UPDATE acessorios
    SET
        nome = ?,
        descricao = ?
    WHERE id = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param(
    "ssi",
    $nome,
    $descricao,
    $id
);

if ($stmt->execute()) {
    header("Location: acessorios.php?sucesso=atualizado");
    exit;
}

echo "Erro ao atualizar acessório: " . $stmt->error;