<?php
require_once "../config/protege.php";
require_once "../config/conexao.php";

$equipamentoId = filter_input(
    INPUT_GET,
    "equipamento_id",
    FILTER_VALIDATE_INT
);

$acessorioId = filter_input(
    INPUT_GET,
    "acessorio_id",
    FILTER_VALIDATE_INT
);

if (!$equipamentoId || !$acessorioId) {
    header("Location: listar.php");
    exit;
}

$sql = "
    DELETE FROM equipamentos_acessorios
    WHERE equipamento_id = ?
    AND acessorio_id = ?
";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "ii",
    $equipamentoId,
    $acessorioId
);

$stmt->execute();

header(
    "Location: visualizar.php?id=" . $equipamentoId
);

exit;