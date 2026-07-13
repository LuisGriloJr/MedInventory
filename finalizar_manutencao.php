<?php
include "config/protege.php";
include "config/conexao.php";

$manutencaoId = (int) $_POST["manutencao_id"];
$dataConclusao = $_POST["data_conclusao"];
$solucaoAplicada = trim($_POST["solucao_aplicada"]);
$garantiaAte = $_POST["garantia_ate"] ?: null;

$conn->begin_transaction();

try {
    $sqlBusca = "
        SELECT equipamento_id, status
        FROM manutencoes
        WHERE id = ?
    ";

    $stmtBusca = $conn->prepare($sqlBusca);
    $stmtBusca->bind_param("i", $manutencaoId);
    $stmtBusca->execute();

    $resultado = $stmtBusca->get_result();
    $manutencao = $resultado->fetch_assoc();

    if (!$manutencao) {
        throw new Exception("Manutenção não encontrada.");
    }

    if ($manutencao["status"] === "Concluída") {
     header(
        "Location: paginas/visualizar_manutencao.php?id="
        . $manutencaoId
        . "&ja_concluida=1"
        );
        exit;
    }

    $sqlAtualizaManutencao = "
        UPDATE manutencoes
        SET
            status = 'Concluída',
            data_conclusao = ?,
            solucao_aplicada = ?,
            garantia_ate = ?
        WHERE id = ?
    ";

    $stmtAtualiza = $conn->prepare($sqlAtualizaManutencao);
    $stmtAtualiza->bind_param(
        "sssi",
        $dataConclusao,
        $solucaoAplicada,
        $garantiaAte,
        $manutencaoId
    );
    $stmtAtualiza->execute();

    $equipamentoId = (int) $manutencao["equipamento_id"];

    $sqlEquipamento = "
        UPDATE equipamentos
        SET status = 'Em uso'
        WHERE id = ?
    ";

    $stmtEquipamento = $conn->prepare($sqlEquipamento);
    $stmtEquipamento->bind_param("i", $equipamentoId);
    $stmtEquipamento->execute();

    $conn->commit();

    header(
    "Location: paginas/visualizar_manutencao.php?id="
    . $manutencaoId
    . "&concluida=1"
);
exit;

} catch (Throwable $erro) {
    $conn->rollback();

    echo "Erro ao concluir manutenção: "
        . htmlspecialchars($erro->getMessage());
}
?>