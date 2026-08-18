<?php
require_once "../config/protege.php";
require_once "../includes/header.php";
require_once "../includes/sidebar.php";
?>

<div class="container py-4">

    <div class="mb-4">
        <h2>Relatórios</h2>
        <p class="text-muted">
            Consulte e imprima informações do inventário e das manutenções.
        </p>
    </div>

    <div class="row g-4">

        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">

                    <h5>📦 Equipamentos</h5>

                    <p class="text-muted">
                        Consulte equipamentos por setor e status.
                    </p>

                    <a
                        href="relatorio_equipamentos.php"
                        class="btn btn-primary"
                    >
                        Abrir relatório
                    </a>

                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">

                    <h5>🛠️ Manutenções</h5>

                    <p class="text-muted">
                        Consulte o histórico de manutenções por período.
                    </p>

                    <a
                        href="relatorio_manutencoes.php"
                        class="btn btn-primary"
                    >
                        Abrir relatório
                    </a>

                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">

                    <h5>💰 Gastos</h5>

                    <p class="text-muted">
                        Consulte os valores gastos com manutenção.
                    </p>

                    <a
                        href="relatorio_gastos.php"
                        class="btn btn-primary"
                    >
                        Abrir relatório
                    </a>

                </div>
            </div>
        </div>

    </div>

</div>

<?php require_once "../includes/footer.php"; ?>