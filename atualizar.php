<?php
include "config/protege.php";
include "config/conexao.php";

$id = $_POST['id'];

$nome = $_POST['nome'];
$fabricante = $_POST['fabricante'];
$modelo = $_POST['modelo'];
$numero_serie = $_POST['numero_serie'];
$setor = $_POST['setor'];
$localizacao = $_POST['localizacao'];
$status = $_POST['status'];
$observacoes = $_POST['observacoes'];

$sql = "SELECT foto FROM equipamentos WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();

$resultado = $stmt->get_result();
$equipamento = $resultado->fetch_assoc();

$foto = $equipamento['foto'];

if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
    $pasta = "uploads/equipamentos/";

    $nomeOriginal = $_FILES['foto']['name'];
    $extensao = pathinfo($nomeOriginal, PATHINFO_EXTENSION);

    $novoNome = uniqid() . "." . $extensao;
    $caminhoFoto = $pasta . $novoNome;

    if (move_uploaded_file($_FILES['foto']['tmp_name'], $caminhoFoto)) {
        if (!empty($foto) && file_exists($foto)) {
            unlink($foto);
        }

        $foto = $caminhoFoto;
    }
}

$sql = "UPDATE equipamentos SET
nome=?,
fabricante=?,
modelo=?,
numero_serie=?,
setor=?,
localizacao=?,
status=?,
foto=?,
observacoes=?
WHERE id=?";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "sssssssssi",
    $nome,
    $fabricante,
    $modelo,
    $numero_serie,
    $setor,
    $localizacao,
    $status,
    $foto,
    $observacoes,
    $id
);

if ($stmt->execute()) {
    header("Location: paginas/visualizar.php?id=" . $id);
    exit;
} else {
    echo "Erro ao atualizar: " . $stmt->error;
}
?>