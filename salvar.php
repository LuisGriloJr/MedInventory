<?php
include "config/conexao.php";

$nome = $_POST['nome'];
$fabricante = $_POST['fabricante'];
$modelo = $_POST['modelo'];
$numero_serie = $_POST['numero_serie'];
$setor = $_POST['setor'];
$localizacao = $_POST['localizacao'];
$status = $_POST['status'];
$observacoes = $_POST['observacoes'];

$foto = null;

if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
    $pasta = "uploads/equipamentos/";

    $nomeOriginal = $_FILES['foto']['name'];
    $extensao = pathinfo($nomeOriginal, PATHINFO_EXTENSION);

    $novoNome = uniqid() . "." . $extensao;
    $caminhoFoto = $pasta . $novoNome;

    if (move_uploaded_file($_FILES['foto']['tmp_name'], $caminhoFoto)) {
        $foto = $caminhoFoto;
    }
}

$sql = "INSERT INTO equipamentos 
(nome, fabricante, modelo, numero_serie, setor, localizacao, status, foto, observacoes)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param(
    "sssssssss",
    $nome,
    $fabricante,
    $modelo,
    $numero_serie,
    $setor,
    $localizacao,
    $status,
    $foto,
    $observacoes
);

if ($stmt->execute()) {
    header("Location: paginas/listar.php?sucesso=1");
    exit;
} else {
    echo "Erro ao salvar: " . $stmt->error;
}
?>