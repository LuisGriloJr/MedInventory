<?php
include "../config/protege.php";
include "../config/conexao.php";

$id = (int) $_GET["id"];

$sql = "
    SELECT
        manutencoes.*,
        equipamentos.nome AS equipamento_nome,
        equipamentos.id AS equipamento_id,
        equipamentos.fabricante,
        equipamentos.modelo,
        empresas.nome AS empresa_nome,
        empresas.telefone AS empresa_telefone,
        empresas.email AS empresa_email,
        usuarios.nome AS usuario_nome
    FROM manutencoes
    INNER JOIN equipamentos
        ON manutencoes.equipamento_id = equipamentos.id
    INNER JOIN empresas
        ON manutencoes.empresa_id = empresas.id
    INNER JOIN usuarios
        ON manutencoes.usuario_id = usuarios.id
    WHERE manutencoes.id = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();

$resultado = $stmt->get_result();
$manutencao = $resultado->fetch_assoc();

if (!$manutencao) {
    echo "Manutenção não encontrada.";
    exit;
}

include "../includes/header.php";
include "../includes/sidebar.php";
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2>Detalhes da Manutenção</h2>
        <p class="text-muted">
            Registro nº <?php echo $manutencao["id"]; ?>
        </p>
    </div>
    <?php if ($manutencao["status"] !== "Concluída"): ?>
    <button
        type="button"
        class="btn btn-success me-2"
        data-bs-toggle="modal"
        data-bs-target="#modalConcluir"
    >
        Concluir manutenção
     </button>
    <?php endif; ?>

    <a
        href="imprimir_os.php?id=<?php echo $manutencao["id"]; ?>"
        class="btn btn-outline-dark me-2"
        
    >
        Imprimir OS
    </a>

    <a
        href="anexos_manutencao.php?manutencao_id=<?php echo $manutencao["id"]; ?>"
        class="btn btn-outline-primary me-2"
    >
        📎 Arquivos
    </a>

    <a href="listar_manutencoes.php" class="btn btn-secondary">
        Voltar
    </a>
    <?php if (isset($_GET["concluida"])): ?>
     <div class="alert alert-success">
        Manutenção concluída com sucesso. O equipamento voltou para Em uso.
     </div>
    <?php endif; ?>

    <?php if (isset($_GET["ja_concluida"])): ?>
    <div class="alert alert-info">
        Esta manutenção já havia sido concluída anteriormente.
    </div>
    <?php endif; ?>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">

        <div class="row g-4">

            <div class="col-md-6">
                <h5>Equipamento</h5>

                <p class="mb-1">
                    <strong>Patrimônio:</strong>
                    <?php echo str_pad($manutencao["equipamento_id"], 4, "0", STR_PAD_LEFT); ?>
                </p>

                <p class="mb-1">
                    <strong>Nome:</strong>
                    <?php echo htmlspecialchars($manutencao["equipamento_nome"]); ?>
                </p>

                <p class="mb-1">
                    <strong>Fabricante:</strong>
                    <?php echo htmlspecialchars($manutencao["fabricante"]); ?>
                </p>

                <p class="mb-1">
                    <strong>Modelo:</strong>
                    <?php echo htmlspecialchars($manutencao["modelo"]); ?>
                </p>
            </div>

            <div class="col-md-6">
                <h5>Empresa responsável</h5>

                <p class="mb-1">
                    <strong>Empresa:</strong>
                    <?php echo htmlspecialchars($manutencao["empresa_nome"]); ?>
                </p>

                <p class="mb-1">
                    <strong>Telefone:</strong>
                    <?php echo htmlspecialchars($manutencao["empresa_telefone"] ?: "-"); ?>
                </p>

                <p class="mb-1">
                    <strong>E-mail:</strong>
                    <?php echo htmlspecialchars($manutencao["empresa_email"] ?: "-"); ?>
                </p>
            </div>

        </div>

        <hr>

        <div class="row g-4">

            <div class="col-md-4">
                <strong>Tipo</strong><br>
                <?php echo htmlspecialchars($manutencao["tipo"]); ?>
            </div>

            <div class="col-md-4">
                <strong>Status</strong><br>
                <?php echo htmlspecialchars($manutencao["status"]); ?>
            </div>

            <div class="col-md-4">
                <strong>Data de abertura</strong><br>
                <?php echo date("d/m/Y", strtotime($manutencao["data_abertura"])); ?>
            </div>

            <div class="col-md-4">
                <strong>Data de conclusão</strong><br>
                <?php
                echo $manutencao["data_conclusao"]
                    ? date("d/m/Y", strtotime($manutencao["data_conclusao"]))
                    : "-";
                ?>
            </div>

            <div class="col-md-4">
                <strong>Número da OS</strong><br>
                <?php echo htmlspecialchars($manutencao["numero_os"] ?: "-"); ?>
            </div>

            <div class="col-md-4">
                <strong>Valor</strong><br>
                <?php
                echo $manutencao["valor"] !== null
                    ? "R$ " . number_format($manutencao["valor"], 2, ",", ".")
                    : "-";
                ?>
            </div>

            <div class="col-md-4">
                <strong>Garantia até</strong><br>
                <?php
                echo $manutencao["garantia_ate"]
                    ? date("d/m/Y", strtotime($manutencao["garantia_ate"]))
                    : "-";
                ?>
            </div>

            <div class="col-md-4">
                <strong>Registrado por</strong><br>
                <?php echo htmlspecialchars($manutencao["usuario_nome"]); ?>
            </div>

        </div>

        <hr>

        <h5>Descrição do problema</h5>
        <div class="border rounded p-3 bg-light mb-4">
            <?php echo nl2br(htmlspecialchars($manutencao["descricao_problema"])); ?>
        </div>

        <h5>Solução aplicada</h5>
        <div class="border rounded p-3 bg-light mb-4">
            <?php
            echo trim($manutencao["solucao_aplicada"])
                ? nl2br(htmlspecialchars($manutencao["solucao_aplicada"]))
                : "<span class='text-muted'>Ainda não informada.</span>";
            ?>
        </div>

        <h5>Observações</h5>
        <div class="border rounded p-3 bg-light">
            <?php
            echo trim($manutencao["observacoes"])
                ? nl2br(htmlspecialchars($manutencao["observacoes"]))
                : "<span class='text-muted'>Nenhuma observação cadastrada.</span>";
            ?>
        </div>

    </div>
</div>

<?php if ($manutencao["status"] !== "Concluída"): ?>

<div
    class="modal fade"
    id="modalConcluir"
    tabindex="-1"
    aria-labelledby="modalConcluirLabel"
    aria-hidden="true"
>
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <form action="../finalizar_manutencao.php" method="POST">

                <div class="modal-header">
                    <h5 class="modal-title" id="modalConcluirLabel">
                        Concluir manutenção
                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Fechar"
                    ></button>
                </div>

                <div class="modal-body">

                    <div class="alert alert-warning">
                        Ao concluir, o equipamento voltará automaticamente para
                        <strong>Em uso</strong>.
                    </div>

                    <input
                        type="hidden"
                        name="manutencao_id"
                        value="<?php echo $manutencao["id"]; ?>"
                    >

                    <div class="mb-3">
                        <label class="form-label">Data de conclusão *</label>

                        <input
                            type="date"
                            name="data_conclusao"
                            class="form-control"
                            value="<?php echo date("Y-m-d"); ?>"
                            required
                        >
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Solução aplicada *</label>

                        <textarea
                            name="solucao_aplicada"
                            class="form-control"
                            rows="5"
                            required
                        ></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Garantia até</label>

                        <input
                            type="date"
                            name="garantia_ate"
                            class="form-control"
                        >
                    </div>

                </div>

                <div class="modal-footer">
                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal"
                    >
                        Cancelar
                    </button>

                    <button type="submit" class="btn btn-success">
                        Confirmar conclusão
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

<?php endif; ?>

<?php include "../includes/footer.php"; ?>