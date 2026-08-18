<?php
require_once "../config/protege.php";
require_once "../config/conexao.php";
require_once "../includes/header.php";
require_once "../includes/sidebar.php";

$dataInicio = trim($_GET["data_inicio"] ?? "");
$dataFim = trim($_GET["data_fim"] ?? "");
$empresaId = trim($_GET["empresa_id"] ?? "");
$tipo = trim($_GET["tipo"] ?? "");

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
| Consulta principal
|--------------------------------------------------------------------------
*/

$sql = "
    SELECT
        manutencoes.id,
        manutencoes.tipo,
        manutencoes.data_abertura,
        manutencoes.valor,

        equipamentos.id AS equipamento_id,
        equipamentos.nome AS equipamento_nome,

        empresas.nome AS empresa_nome

    FROM manutencoes

    INNER JOIN equipamentos
        ON equipamentos.id = manutencoes.equipamento_id

    INNER JOIN empresas
        ON empresas.id = manutencoes.empresa_id

    WHERE manutencoes.valor IS NOT NULL
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

$gastos = $stmt->get_result();

/*
|--------------------------------------------------------------------------
| Somatórios
|--------------------------------------------------------------------------
*/

$totalGasto = 0;
$quantidade = $gastos->num_rows;

$linhas = [];

while ($item = $gastos->fetch_assoc()) {

    $totalGasto += (float) $item["valor"];

    $linhas[] = $item;
}
?>

<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2>Relatório de Gastos</h2>

            <p class="text-muted mb-0">
                Consulte os valores registrados nas manutenções.
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

    <!-- FILTROS -->

    <div class="card shadow-sm border-0 mb-4 nao-imprimir">

        <div class="card-body">

            <form
                method="GET"
                action="relatorio_gastos.php"
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
                                <?php
                                if ($tipo === "Corretiva") {
                                    echo "selected";
                                }
                                ?>
                            >
                                Corretiva
                            </option>

                            <option
                                value="Preventiva"
                                <?php
                                if ($tipo === "Preventiva") {
                                    echo "selected";
                                }
                                ?>
                            >
                                Preventiva
                            </option>

                        </select>

                    </div>

                    <div class="col-md-12">

                        <button
                            type="submit"
                            class="btn btn-primary"
                        >
                            Filtrar
                        </button>

                        <a
                            href="relatorio_gastos.php"
                            class="btn btn-secondary"
                        >
                            Limpar
                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>

    <!-- RESUMO -->

    <div class="row g-4 mb-4">

        <div class="col-md-6">

            <div class="card shadow-sm border-0">

                <div class="card-body">

                    <div class="text-muted">
                        Total gasto
                    </div>

                    <h2 class="mb-0">
                        R$
                        <?php
                        echo number_format(
                            $totalGasto,
                            2,
                            ",",
                            "."
                        );
                        ?>
                    </h2>

                </div>

            </div>

        </div>

        <div class="col-md-6">

            <div class="card shadow-sm border-0">

                <div class="card-body">

                    <div class="text-muted">
                        Manutenções com valor registrado
                    </div>

                    <h2 class="mb-0">
                        <?php echo $quantidade; ?>
                    </h2>

                </div>

            </div>

        </div>

    </div>

    <!-- TABELA -->

    <div class="card shadow-sm border-0">

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-striped table-bordered align-middle">

                    <thead>

                        <tr>
                            <th>Data</th>
                            <th>Patrimônio</th>
                            <th>Equipamento</th>
                            <th>Tipo</th>
                            <th>Empresa</th>
                            <th>Valor</th>
                        </tr>

                    </thead>

                    <tbody>

                    <?php if (count($linhas) > 0): ?>

                        <?php foreach ($linhas as $item): ?>

                            <tr>

                                <td>
                                    <?php
                                    echo date(
                                        "d/m/Y",
                                        strtotime($item["data_abertura"])
                                    );
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    echo str_pad(
                                        $item["equipamento_id"],
                                        4,
                                        "0",
                                        STR_PAD_LEFT
                                    );
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    echo htmlspecialchars(
                                        $item["equipamento_nome"]
                                    );
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    echo htmlspecialchars(
                                        $item["tipo"]
                                    );
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    echo htmlspecialchars(
                                        $item["empresa_nome"]
                                    );
                                    ?>
                                </td>

                                <td>
                                    R$
                                    <?php
                                    echo number_format(
                                        $item["valor"],
                                        2,
                                        ",",
                                        "."
                                    );
                                    ?>
                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>

                            <td
                                colspan="6"
                                class="text-center text-muted py-4"
                            >
                                Nenhuma manutenção com valor registrado.
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
    }

}
</style>

<?php require_once "../includes/footer.php"; ?>