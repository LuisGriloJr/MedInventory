<?php
require_once "../config/protege.php";
require_once "../config/conexao.php";

$equipamentoId = filter_input(INPUT_POST, "equipamento_id", FILTER_VALIDATE_INT);
$acessorioId = filter_input(INPUT_POST, "acessorio_id", FILTER_VALIDATE_INT);

if (!$equipamentoId || !$acessorioId) {
    header("Location: listar.php");
    exit;
}

$sql = "
    INSERT IGNORE INTO equipamentos_acessorios (
        equipamento_id,
        acessorio_id
    )
    VALUES (?, ?)
";

$stmt = $conn->prepare($sql);
$stmt->bind_param(
    "ii",
    $equipamentoId,
    $acessorioId
);

$stmt->execute();

header(
    "Location: visualizar.php?id="
    . $equipamentoId
);

exit;