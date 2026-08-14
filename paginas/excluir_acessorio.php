<?php
require_once "../config/protege.php";
require_once "../config/conexao.php";

$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if (!$id) {
    header("Location: acessorios.php?erro=nao_encontrado");
    exit;
}

$sql = "DELETE FROM acessorios WHERE id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: acessorios.php?sucesso=excluido");
    exit;
}

header("Location: acessorios.php?erro=exclusao");
exit;