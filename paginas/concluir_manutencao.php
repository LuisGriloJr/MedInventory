<?php
include "../config/protege.php";
include "../config/conexao.php";

$id = (int) $_GET["id"];

$sql = "
    SELECT
        manutencoes.id,
        manutencoes.status,
        manutencoes.equipamento_id,
        manutencoes.descricao_problema,
        equipamentos.nome AS equipamento_nome
    FROM manutencoes
    INNER JOIN equipamentos
        ON manutencoes.equipamento_id = equipamentos.id
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

if ($manutencao["status"] === "Concluída") {
    header("Location: visualizar_manutencao.php?id=" . $id);
    exit;
}

include "../includes/header.php";
include "../includes/sidebar.php";
?>

<div class="mb-4">
    <h2>Concluir Manutenção</h2>

    <p class="text-muted">
        Equipamento:
        <strong>
            <?php echo htmlspecialchars($manutencao["equipamento_nome"]); ?>
        </strong>
    </p>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">

        <div class="alert alert-warning">
            Ao concluir esta manutenção, o equipamento voltará automaticamente
            para o status <strong>Em uso</strong>.
        </div>

        <form action="../finalizar_manutencao.php" method="POST">

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
                    placeholder="Descreva o serviço executado e a solução aplicada"
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

            <button type="submit" class="btn btn-success">
                Concluir manutenção
            </button>

            <a
                href="visualizar_manutencao.php?id=<?php echo $manutencao["id"]; ?>"
                class="btn btn-secondary"
            >
                Cancelar
            </a>

        </form>

    </div>
</div>

<?php include "../includes/footer.php"; ?>