<?php
require_once "../config/protege.php";
require_once "../config/conexao.php";
require_once "../includes/header.php";
require_once "../includes/sidebar.php";

$dataInicio = trim($_GET["data_inicio"] ?? "");
$dataFim = trim($_GET["data_fim"] ?? "");
$empresaId = trim($_GET["empresa_id"] ?? "");
$tipo = trim($_GET["tipo"] ?? "");
$status = trim($_GET["status"] ?? "");

/*
|--------------------------------------------------------------------------
| Empresas para o filtro
|--------------------------------------------------------------------------
*/

$sqlEmpresas = "
    SELECT id, nome
    FROM empresas
    ORDER BY nome ASC
";

$empresas = $conn->query($sqlEmpresas);

/*
|--------------------------------------------------------------------------
| Montagem da consulta
|--------------------------------------------------------------------------
*/

$sql = "
    SELECT
        manutencoes.id,
        manutencoes.tipo,
        manutencoes.status,
        manutencoes.data_abertura,
        manutencoes.data_conclusao,
        manutencoes.numero_os,
        manutencoes.valor,

        equipamentos.id AS equipamento_id,
        equipamentos.nome AS equipamento_nome,

        empresas.nome AS empresa_nome

    FROM manutencoes

    INNER JOIN equipamentos
        ON equipamentos.id = manutencoes.equipamento_id

    INNER JOIN empresas
        ON empresas.id = manutencoes.empresa_id

    WHERE 1 = 1
";

$tipos = "";
$parametros = [];

if ($dataInicio !== "") {
    $sql .= " AND manutencoes.data_abertura >= ?";
    $tipos .= "s";
    $parametros[] = $dataInicio;
}

if ($dataFim !== "") {
    $sql .= " AND manutencoes.data_abertura <= ?";
    $tipos .= "s";
    $parametros[] = $dataFim;
}

if ($empresaId !== "") {
    $sql .= " AND manutencoes.empresa_id = ?";
    $tipos .= "i";
    $parametros[] = (int) $empresaId;
}

if ($tipo !== "") {
    $sql .= " AND manutencoes.tipo = ?";
    $tipos .= "s";
    $parametros[] = $tipo;
}

if ($status !== "") {
    $sql .= " AND manutencoes.status = ?";
    $tipos .= "s";
    $parametros[] = $status;
}

$sql .= "
    ORDER BY
        manutencoes.data_abertura DESC,
        manutencoes.id DESC
";

$stmt = $conn->prepare($sql);

if (!empty($parametros)) {
    $stmt->bind_param(
        $tipos,
        ...$parametros
    );
}

$stmt->execute();

$manutencoes = $stmt->get_result();
?>

<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2>Relatório de Manutenções</h2>

            <p class="text-muted mb-0">
                Consulte o histórico de manutenções registradas no sistema.
            </p>
        </div>

        <button
            type="button"
            class="btn btn-outline-primary nao-imprimir"
            onclick="window.print()"
        >
            🖨️ Imprimir
        </button>

    </div>

    <div class="card shadow-sm border-0 mb-4 nao-imprimir">

        <div class="card-body">

            <form
                method="GET"
                action="relatorio_manutencoes.php"
            >

                <div class="row g-3">

                    <div class="col-md-3">
                        <label class="form-label">
                            Data inicial
                        </label>

                        <input
                            type="date"
                            name="data_inicio"
                            class="form-control"
                            value="<?php echo htmlspecialchars($dataInicio); ?>"
                        >
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">
                            Data final
                        </label>

                        <input
                            type="date"
                            name="data_fim"
                            class="form-control"
                            value="<?php echo htmlspecialchars($dataFim); ?>"
                        >
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">
                            Empresa
                        </label>

                        <select
                            name="empresa_id"
                            class="form-select"
                        >

                            <option value="">
                                Todas as empresas
                            </option>

                            <?php while ($empresa = $empresas->fetch_assoc()): ?>

                                <option
                                    value="<?php echo (int) $empresa["id"]; ?>"
                                    <?php
                                    if ($empresaId == $empresa["id"]) {
                                        echo "selected";
                                    }
                                    ?>
                                >
                                    <?php
                                    echo htmlspecialchars(
                                        $empresa["nome"]
                                    );
                                    ?>
                                </option>

                            <?php endwhile; ?>

                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">
                            Tipo
                        </label>

                        <select
                            name="tipo"
                            class="form-select"
                        >

                            <option value="">
                                Todos os tipos
                            </option>

                            <option
                                value="Corretiva"
                                <?php if ($tipo === "Corretiva") echo "selected"; ?>
                            >
                                Corretiva
                            </option>

                            <option
                                value="Preventiva"
                                <?php if ($tipo === "Preventiva") echo "selected"; ?>
                            >
                                Preventiva
                            </option>

                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">
                            Status
                        </label>

                        <select
                            name="status"
                            class="form-select"
                        >

                            <option value="">
                                Todos os status
                            </option>

                            <option
                                value="Aberta"
                                <?php if ($status === "Aberta") echo "selected"; ?>
                            >
                                Aberta
                            </option>

                            <option
                                value="Em andamento"
                                <?php if ($status === "Em andamento") echo "selected"; ?>
                            >
                                Em andamento
                            </option>

                            <option
                                value="Concluída"
                                <?php if ($status === "Concluída") echo "selected"; ?>
                            >
                                Concluída
                            </option>

                        </select>
                    </div>

                    <div class="col-md-3 d-flex align-items-end gap-2">

                        <button
                            type="submit"
                            class="btn btn-primary"
                        >
                            Filtrar
                        </button>

                        <a
                            href="relatorio_manutencoes.php"
                            class="btn btn-secondary"
                        >
                            Limpar
                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>

    <div class="card shadow-sm border-0">

        <div class="card-body">

            <div class="mb-3">

                <strong>
                    Total encontrado:
                    <?php echo $manutencoes->num_rows; ?>
                </strong>

                <?php if ($dataInicio !== ""): ?>
                    <span class="ms-3">
                        De:
                        <strong>
                            <?php echo date("d/m/Y", strtotime($dataInicio)); ?>
                        </strong>
                    </span>
                <?php endif; ?>

                <?php if ($dataFim !== ""): ?>
                    <span class="ms-3">
                        Até:
                        <strong>
                            <?php echo date("d/m/Y", strtotime($dataFim)); ?>
                        </strong>
                    </span>
                <?php endif; ?>

                <?php if ($tipo !== ""): ?>
                    <span class="ms-3">
                        Tipo:
                        <strong>
                            <?php echo htmlspecialchars($tipo); ?>
                        </strong>
                    </span>
                <?php endif; ?>

                <?php if ($status !== ""): ?>
                    <span class="ms-3">
                        Status:
                        <strong>
                            <?php echo htmlspecialchars($status); ?>
                        </strong>
                    </span>
                <?php endif; ?>

            </div>

            <div class="table-responsive">

                <table class="table table-striped table-bordered align-middle">

                    <thead>

                        <tr>
                            <th>Patrimônio</th>
                            <th>Equipamento</th>
                            <th>Tipo</th>
                            <th>Empresa</th>
                            <th>OS</th>
                            <th>Abertura</th>
                            <th>Conclusão</th>
                            <th>Status</th>
                            <th>Valor</th>
                        </tr>

                    </thead>

                    <tbody>

                    <?php if ($manutencoes->num_rows > 0): ?>

                        <?php while ($manutencao = $manutencoes->fetch_assoc()): ?>

                            <tr>

                                <td>
                                    <?php
                                    echo str_pad(
                                        $manutencao["equipamento_id"],
                                        4,
                                        "0",
                                        STR_PAD_LEFT
                                    );
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    echo htmlspecialchars(
                                        $manutencao["equipamento_nome"]
                                    );
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    echo htmlspecialchars(
                                        $manutencao["tipo"]
                                    );
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    echo htmlspecialchars(
                                        $manutencao["empresa_nome"]
                                    );
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    echo htmlspecialchars(
                                        $manutencao["numero_os"] ?: "-"
                                    );
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    echo date(
                                        "d/m/Y",
                                        strtotime($manutencao["data_abertura"])
                                    );
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    echo $manutencao["data_conclusao"]
                                        ? date(
                                            "d/m/Y",
                                            strtotime($manutencao["data_conclusao"])
                                        )
                                        : "-";
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    echo htmlspecialchars(
                                        $manutencao["status"]
                                    );
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    echo $manutencao["valor"] !== null
                                        ? "R$ " . number_format(
                                            $manutencao["valor"],
                                            2,
                                            ",",
                                            "."
                                        )
                                        : "-";
                                    ?>
                                </td>

                            </tr>

                        <?php endwhile; ?>

                    <?php else: ?>

                        <tr>

                            <td
                                colspan="9"
                                class="text-center text-muted py-4"
                            >
                                Nenhuma manutenção encontrada
                                para os filtros selecionados.
                            </td>

                        </tr>

                    <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

<style>
@media print {

    @page {
        size: A4 landscape;
        margin: 10mm;
    }

    body {
        background: white !important;
        font-size: 10px;
    }

    .navbar,
    .sidebar,
    .nao-imprimir {
        display: none !important;
    }

    .conteudo {
        margin: 0 !important;
        padding: 0 !important;
        width: 100% !important;
    }

    .container {
        width: 100% !important;
        max-width: 100% !important;
        padding: 0 !important;
        margin: 0 !important;
    }

    .card {
        border: none !important;
        box-shadow: none !important;
    }

    .card-body {
        padding: 0 !important;
    }

    .table-responsive {
        overflow: visible !important;
    }

    table {
        width: 100% !important;
        font-size: 9px;
    }

    th,
    td {
        padding: 4px !important;
        white-space: normal !important;
        word-break: break-word;
    }

    h2 {
        font-size: 18px;
    }
}
</style>

<?php require_once "../includes/footer.php"; ?>