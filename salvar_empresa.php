<?php
include "config/protege.php";
include "config/conexao.php";

$nome = trim($_POST["nome"]);
$contato = trim($_POST["contato"]);
$telefone = trim($_POST["telefone"]);
$email = trim($_POST["email"]);
$observacoes = trim($_POST["observacoes"]);

$sql = "INSERT INTO empresas
(nome, contato, telefone, email, observacoes)
VALUES (?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param(
    "sssss",
    $nome,
    $contato,
    $telefone,
    $email,
    $observacoes
);

if ($stmt->execute()) {
    header("Location: paginas/empresas.php?sucesso=1");
    exit;
}

echo "Erro ao cadastrar empresa: " . $stmt->error;