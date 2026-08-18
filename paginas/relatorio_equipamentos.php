<?php
require_once "../config/protege.php";
require_once "../config/conexao.php";
require_once "../includes/header.php";
require_once "../includes/sidebar.php";

$setor = trim($_GET["setor"] ?? "");
$status = trim($_GET["status"] ?? "");

/*
|--------------------------------------------------------------------------
| Buscar setores disponíveis
|--------------------------------------------------------------------------
*/

$sqlSetores = "
    SELECT DISTINCT setor
    FROM equipamentos
    WHERE setor IS NOT NULL
      AND setor <> ''
    ORDER BY setor ASC
";

$setores = $conn->query($sqlSetores);

/*
|--------------------------------------------------------------------------
| Montar consulta dos equipamentos
|--------------------------------------------------------------------------
*/

$sql = "
    SELECT
        id,
        nome,
        fabricante,
        modelo,
        numero_serie,
        setor,
        localizacao,
        status
    FROM equipamentos
    WHERE 1 = 1
";

$tipos = "";
$parametros = [];

if ($setor !== "") {
    $sql .= " AND setor = ?";
    $tipos .= "s";
    $parametros[] = $setor;
}

if ($status !== "") {
    $sql .= " AND status = ?";
    $tipos .= "s";
    $parametros[] = $status;
}

$sql .= "
    ORDER BY
        setor ASC,
        nome ASC
";

$stmt = $conn->prepare($sql);

if (!empty($parametros)) {
    $stmt->bind_param(
        $tipos,
        ...$parametros
    );
}

$stmt->execute();

$equipamentos = $stmt->get_result();
?>

<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2>
                Relatório de Equipamentos
            </h2>

            <p class="text-muted mb-0">
                Consulte os equipamentos cadastrados no inventário.
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
                action="relatorio_equipamentos.php"
            >

                <div class="row g-3 align-items-end">

                    <!-- SETOR -->

                    <div class="col-md-5">

                        <label class="form-label">
                            Setor
                        </label>

                        <select
                            name="setor"
                            class="form-select"
                        >

                            <option value="">
                                Todos os setores
                            </option>

                            <?php while ($itemSetor = $setores->fetch_assoc()): ?>

                                <option
                                    value="<?php echo htmlspecialchars($itemSetor["setor"]); ?>"
                                    <?php
                                    if ($setor === $itemSetor["setor"]) {
                                        echo "selected";
                                    }
                                    ?>
                                >
                                    <?php
                                    echo htmlspecialchars(
                                        $itemSetor["setor"]
                                    );
                                    ?>
                                </option>

                            <?php endwhile; ?>

                        </select>

                    </div>

                    <!-- STATUS -->

                    <div class="col-md-4">

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
                                value="Em uso"
                                <?php
                                if ($status === "Em uso") {
                                    echo "selected";
                                }
                                ?>
                            >
                                Em uso
                            </option>

                            <option
                                value="Manutenção"
                                <?php
                                if ($status === "Manutenção") {
                                    echo "selected";
                                }
                                ?>
                            >
                                Manutenção
                            </option>

                            <option
                                value="Baixado"
                                <?php
                                if ($status === "Baixado") {
                                    echo "selected";
                                }
                                ?>
                            >
                                Baixado
                            </option>

                            <option
                                value="Desativado"
                                <?php
                                if ($status === "Desativado") {
                                    echo "selected";
                                }
                                ?>
                            >
                                Desativado
                            </option>

                        </select>

                    </div>

                    <!-- BOTÕES -->

                    <div class="col-md-3">

                        <button
                            type="submit"
                            class="btn btn-primary"
                        >
                            Filtrar
                        </button>

                        <a
                            href="relatorio_equipamentos.php"
                            class="btn btn-secondary"
                        >
                            Limpar
                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>

    <!-- RELATÓRIO -->

    <div class="card shadow-sm border-0">

        <div class="card-body">

            <div class="mb-3">

                <strong>
                    Total encontrado:
                    <?php echo $equipamentos->num_rows; ?>
                </strong>

                <?php if ($setor !== ""): ?>

                    <span class="ms-3">
                        Setor:
                        <strong>
                            <?php echo htmlspecialchars($setor); ?>
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
                            <th>Fabricante</th>
                            <th>Modelo</th>
                            <th>Série</th>
                            <th>Setor</th>
                            <th>Localização</th>
                            <th>Status</th>
                        </tr>

                    </thead>

                    <tbody>

                    <?php if ($equipamentos->num_rows > 0): ?>

                        <?php while ($equipamento = $equipamentos->fetch_assoc()): ?>

                            <tr>

                                <td>
                                    <?php
                                    echo str_pad(
                                        $equipamento["id"],
                                        4,
                                        "0",
                                        STR_PAD_LEFT
                                    );
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    echo htmlspecialchars(
                                        $equipamento["nome"]
                                    );
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    echo htmlspecialchars(
                                        $equipamento["fabricante"]
                                    );
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    echo htmlspecialchars(
                                        $equipamento["modelo"]
                                    );
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    echo htmlspecialchars(
                                        $equipamento["numero_serie"]
                                    );
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    echo htmlspecialchars(
                                        $equipamento["setor"]
                                    );
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    echo htmlspecialchars(
                                        $equipamento["localizacao"]
                                    );
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    echo htmlspecialchars(
                                        $equipamento["status"]
                                    );
                                    ?>
                                </td>

                            </tr>

                        <?php endwhile; ?>

                    <?php else: ?>

                        <tr>

                            <td
                                colspan="8"
                                class="text-center text-muted py-4"
                            >
                                Nenhum equipamento encontrado
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

/*
|--------------------------------------------------------------------------
| Impressão
|--------------------------------------------------------------------------
*/

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
        table-layout: auto;
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

    p {
        font-size: 10px;
    }
}

</style>

<?php require_once "../includes/footer.php"; ?>