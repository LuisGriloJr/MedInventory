<?php
include "../config/protege.php";
include "../config/conexao.php";

$manutencaoId = (int) ($_GET["manutencao_id"] ?? 0);

$sqlManutencao = "
    SELECT
        manutencoes.id,
        equipamentos.id AS equipamento_id,
        equipamentos.nome AS equipamento_nome
    FROM manutencoes
    INNER JOIN equipamentos
        ON equipamentos.id = manutencoes.equipamento_id
    WHERE manutencoes.id = ?
";

$stmt = $conn->prepare($sqlManutencao);
$stmt->bind_param("i", $manutencaoId);
$stmt->execute();

$resultadoManutencao = $stmt->get_result();
$manutencao = $resultadoManutencao->fetch_assoc();

if (!$manutencao) {
    echo "Manutenção não encontrada.";
    exit;
}

$sqlAnexos = "
    SELECT *
    FROM anexos_manutencao
    WHERE manutencao_id = ?
    ORDER BY data_upload DESC
";

$stmtAnexos = $conn->prepare($sqlAnexos);
$stmtAnexos->bind_param("i", $manutencaoId);
$stmtAnexos->execute();

$anexos = $stmtAnexos->get_result();

include "../includes/header.php";
include "../includes/sidebar.php";
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2>Arquivos da Manutenção</h2>

        <p class="text-muted">
            Manutenção nº <?php echo $manutencao["id"]; ?>
            —
            Patrimônio
            <?php
            echo str_pad(
                $manutencao["equipamento_id"],
                4,
                "0",
                STR_PAD_LEFT
            );
            ?>
            —
            <?php echo htmlspecialchars($manutencao["equipamento_nome"]); ?>
        </p>
    </div>

    <a
        href="visualizar_manutencao.php?id=<?php echo $manutencaoId; ?>"
        class="btn btn-secondary"
    >
        Voltar
    </a>
</div>

<?php if (isset($_GET["sucesso"])): ?>
    <div class="alert alert-success">
        Arquivo enviado com sucesso.
    </div>
<?php endif; ?>

<div class="row g-4">

    <div class="col-md-5">
        <div class="card shadow-sm border-0">
            <div class="card-body">

                <h5 class="mb-3">Enviar arquivo</h5>

                <form
                    action="../salvar_anexo_manutencao.php"
                    method="POST"
                    enctype="multipart/form-data"
                >
                    <input
                        type="hidden"
                        name="manutencao_id"
                        value="<?php echo $manutencaoId; ?>"
                    >

                    <div class="mb-3">
                        <label class="form-label">Arquivo *</label>

                        <input
                            type="file"
                            name="arquivo"
                            class="form-control"
                            required
                        >
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Descrição</label>

                        <input
                            type="text"
                            name="descricao"
                            class="form-control"
                            placeholder="Ex.: Certificado de calibração 2026"
                        >
                    </div>

                    <button type="submit" class="btn btn-primary">
                        Enviar arquivo
                    </button>
                </form>

            </div>
        </div>
    </div>

    <div class="col-md-7">
        <div class="card shadow-sm border-0">
            <div class="card-body">

                <h5 class="mb-3">Arquivos enviados</h5>

                <?php if ($anexos->num_rows > 0): ?>

                    <div class="list-group">

                        <?php while ($anexo = $anexos->fetch_assoc()): ?>

                            <div class="list-group-item">

                                <div class="d-flex justify-content-between align-items-center">

                                    <div>
                                        <strong>
                                            <?php
                                            echo htmlspecialchars(
                                                $anexo["descricao"]
                                                ?: $anexo["nome_original"]
                                            );
                                            ?>
                                        </strong>

                                        <div class="small text-muted">
                                            <?php
                                            echo htmlspecialchars(
                                                $anexo["nome_original"]
                                            );
                                            ?>
                                            —
                                            <?php
                                            echo date(
                                                "d/m/Y H:i",
                                                strtotime($anexo["data_upload"])
                                            );
                                            ?>
                                        </div>
                                    </div>

                                    <div class="btn-group">

                                        <a
                                            href="../uploads/manutencoes/<?php
                                                 echo $manutencaoId;
                                            ?>/<?php
                                                echo rawurlencode($anexo["nome_arquivo"]);
                                            ?>"
                                            class="btn btn-sm btn-outline-primary"
                                            target="_blank"
                                        >
                                            Abrir
                                        </a>

                                        <a
                                            href="../excluir_anexo.php?id=<?php echo $anexo["id"]; ?>"
                                            class="btn btn-sm btn-outline-danger"
                                            onclick="return confirm('Deseja realmente excluir este arquivo?');"
                                        >
                                            Excluir
                                        </a>

                                    </div>

                                </div>

                            </div>

                        <?php endwhile; ?>

                    </div>

                <?php else: ?>

                    <div class="text-center text-muted py-4">
                        Nenhum arquivo enviado para esta manutenção.
                    </div>

                <?php endif; ?>

            </div>
        </div>
    </div>

</div>

<?php include "../includes/footer.php"; ?>