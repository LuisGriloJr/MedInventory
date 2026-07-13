<?php

include "config/protege.php";
include "config/conexao.php";

$manutencaoId = (int) $_POST["manutencao_id"];
$descricao = trim($_POST["descricao"]);

if (!isset($_FILES["arquivo"])) {
    die("Nenhum arquivo enviado.");
}

$arquivo = $_FILES["arquivo"];

if ($arquivo["error"] != UPLOAD_ERR_OK) {
    die("Erro no upload.");
}

$pasta = "uploads/manutencoes/" . $manutencaoId;

if (!is_dir($pasta)) {
    mkdir($pasta, 0777, true);
}

$nomeOriginal = $arquivo["name"];

$extensao = strtolower(
    pathinfo($nomeOriginal, PATHINFO_EXTENSION)
);

$nomeArquivo = uniqid() . "." . $extensao;

$caminhoDestino = $pasta . "/" . $nomeArquivo;

if (!move_uploaded_file($arquivo["tmp_name"], $caminhoDestino)) {
    die("Erro ao salvar o arquivo.");
}

$tamanho = filesize($caminhoDestino);

$sql = "
INSERT INTO anexos_manutencao
(
manutencao_id,
nome_original,
nome_arquivo,
descricao,
tipo,
tamanho
)

VALUES
(
?,
?,
?,
?,
?,
?
)
";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "issssi",
    $manutencaoId,
    $nomeOriginal,
    $nomeArquivo,
    $descricao,
    $extensao,
    $tamanho
);

$stmt->execute();

header(
    "Location: paginas/anexos_manutencao.php?manutencao_id="
    . $manutencaoId
    . "&sucesso=1"
);

exit;