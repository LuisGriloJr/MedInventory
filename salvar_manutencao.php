<?php
include "config/protege.php";
include "config/conexao.php";

$equipamentoId = (int) $_POST["equipamento_id"];
$empresaId = (int) $_POST["empresa_id"];
$usuarioId = (int) $_SESSION["usuario_id"];

$tipo = $_POST["tipo"];
$status = $_POST["status"];
$dataAbertura = $_POST["data_abertura"];
$descricaoProblema = trim($_POST["descricao_problema"]);
$numeroOs = trim($_POST["numero_os"]);
$observacoes = trim($_POST["observacoes"]);

$valor = null;

if (isset($_POST["valor"]) && $_POST["valor"] !== "") {
    $valor = (float) $_POST["valor"];
}

$conn->begin_transaction();

try {
    $sql = "INSERT INTO manutencoes (
                equipamento_id,
                empresa_id,
                usuario_id,
                tipo,
                status,
                data_abertura,
                numero_os,
                descricao_problema,
                valor,
                observacoes
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "iiisssssds",
        $equipamentoId,
        $empresaId,
        $usuarioId,
        $tipo,
        $status,
        $dataAbertura,
        $numeroOs,
        $descricaoProblema,
        $valor,
        $observacoes
    );

    $stmt->execute();

    $sqlEquipamento = "
        UPDATE equipamentos
        SET status = 'Manutenção'
        WHERE id = ?
    ";

    $stmtEquipamento = $conn->prepare($sqlEquipamento);
    $stmtEquipamento->bind_param("i", $equipamentoId);
    $stmtEquipamento->execute();

    $conn->commit();

    header("Location: paginas/listar_manutencoes.php?sucesso=1");
    exit;

} catch (Throwable $erro) {
    $conn->rollback();

    echo "Erro ao salvar manutenção: " . $erro->getMessage();
}
?>