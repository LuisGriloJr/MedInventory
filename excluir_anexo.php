<?php

include "config/protege.php";
include "config/conexao.php";

$id = (int) ($_GET["id"] ?? 0);

$sql = "
SELECT *
FROM anexos_manutencao
WHERE id = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();

$resultado = $stmt->get_result();

$anexo = $resultado->fetch_assoc();

if (!$anexo) {
    die("Arquivo não encontrado.");
}

$caminho = "uploads/manutencoes/"
    . $anexo["manutencao_id"]
    . "/"
    . $anexo["nome_arquivo"];

if (file_exists($caminho)) {
    unlink($caminho);
}

$sql = "
DELETE
FROM anexos_manutencao
WHERE id = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();

header(
    "Location: paginas/anexos_manutencao.php?manutencao_id="
    . $anexo["manutencao_id"]
);

exit;