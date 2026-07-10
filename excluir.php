<?php
include "config/protege.php";
include "config/conexao.php";

if (!isset($_GET['id'])) {
    header("Location: paginas/listar.php");
    exit;
}

$id = $_GET['id'];

// Buscar a foto antes de excluir
$sql = "SELECT foto FROM equipamentos WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();

$resultado = $stmt->get_result();
$equipamento = $resultado->fetch_assoc();

if (!$equipamento) {
    header("Location: paginas/listar.php");
    exit;
}

// Excluir do banco
$sql = "DELETE FROM equipamentos WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {

    // Excluir foto do servidor
    if (!empty($equipamento['foto']) && file_exists($equipamento['foto'])) {
        unlink($equipamento['foto']);
    }

    header("Location: paginas/listar.php?excluido=1");
    exit;
} else {
    echo "Erro ao excluir: " . $stmt->error;
}
?>