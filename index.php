<?php
include "config/protege.php";
include "config/conexao.php";

$sql = "SELECT COUNT(*) AS total FROM equipamentos";
$resultado = $conn->query($sql);
$total = $resultado->fetch_assoc()["total"];

$sqlUso = "SELECT COUNT(*) AS total FROM equipamentos WHERE status = 'Em uso'";
$resUso = $conn->query($sqlUso);
$totalUso = $resUso->fetch_assoc()["total"];

$sqlManutencao = "SELECT COUNT(*) AS total FROM equipamentos WHERE status = 'Manutenção'";
$resManutencao = $conn->query($sqlManutencao);
$totalManutencao = $resManutencao->fetch_assoc()["total"];

$sqlUltimasManutencoes = "
    SELECT
        manutencoes.id,
        manutencoes.tipo,
        manutencoes.status,
        manutencoes.data_abertura,
        equipamentos.id AS equipamento_id,
        equipamentos.nome AS equipamento_nome,
        empresas.nome AS empresa_nome
    FROM manutencoes
    INNER JOIN equipamentos
        ON equipamentos.id = manutencoes.equipamento_id
    INNER JOIN empresas
        ON empresas.id = manutencoes.empresa_id
    ORDER BY manutencoes.id DESC
    LIMIT 5
";

$ultimasManutencoes = $conn->query($sqlUltimasManutencoes);

$sqlEquipamentosManutencao = "
    SELECT
        equipamentos.id,
        equipamentos.nome,
        manutencoes.id AS manutencao_id,
        manutencoes.tipo,
        manutencoes.data_abertura
    FROM equipamentos

    INNER JOIN manutencoes
        ON manutencoes.equipamento_id = equipamentos.id

    WHERE manutencoes.status IN ('Aberta','Em andamento')

    ORDER BY manutencoes.data_abertura ASC

    LIMIT 5
";

$equipamentosManutencao = $conn->query($sqlEquipamentosManutencao);

include "includes/header.php";
include "includes/sidebar.php";
?>

<div class="mb-4">
    <h2>Dashboard</h2>
    <p class="text-muted">
        Bem-vindo ao MedInventory, <?php echo $_SESSION["usuario_nome"]; ?>.
    </p>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h6 class="text-muted">Total de equipamentos</h6>
                <h1><?php echo $total; ?></h1>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h6 class="text-muted">Em uso</h6>
                <h1><?php echo $totalUso; ?></h1>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h6 class="text-muted">Em manutenção</h6>
                <h1><?php echo $totalManutencao; ?></h1>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <h5>Ações rápidas</h5>

        <a href="paginas/cadastrar.php" class="btn btn-primary">
            + Novo Equipamento
        </a>

        <a href="paginas/listar.php" class="btn btn-secondary">
            Listar Equipamentos
        </a>
    </div>
</div>

<div class="card shadow-sm border-0 mt-4">
    <div class="card-body">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Últimas manutenções</h5>

            <a
                href="paginas/listar_manutencoes.php"
                class="btn btn-sm btn-outline-primary"
            >
                Ver todas
            </a>
        </div>

        <?php if ($ultimasManutencoes->num_rows > 0): ?>

            <div class="list-group list-group-flush">

                <?php while ($manutencao = $ultimasManutencoes->fetch_assoc()): ?>

                    <?php
                    $cor = "secondary";

                    if ($manutencao["status"] === "Aberta") {
                        $cor = "danger";
                    }

                    if ($manutencao["status"] === "Em andamento") {
                        $cor = "warning";
                    }

                    if ($manutencao["status"] === "Concluída") {
                        $cor = "success";
                    }
                    ?>

                    <a
                        href="paginas/visualizar_manutencao.php?id=<?php echo $manutencao['id']; ?>"
                        class="list-group-item list-group-item-action"
                    >
                        <div class="d-flex justify-content-between align-items-start">

                            <div>
                                <strong>
                                    Patrimônio
                                    <?php
                                    echo str_pad(
                                        $manutencao["equipamento_id"],
                                        4,
                                        "0",
                                        STR_PAD_LEFT
                                    );
                                    ?>
                                    -
                                    <?php
                                    echo htmlspecialchars(
                                        $manutencao["equipamento_nome"]
                                    );
                                    ?>
                                </strong>

                                <div class="text-muted small mt-1">
                                    <?php echo htmlspecialchars($manutencao["tipo"]); ?>
                                    |
                                    <?php echo htmlspecialchars($manutencao["empresa_nome"]); ?>
                                    |
                                    <?php
                                    echo date(
                                        "d/m/Y",
                                        strtotime($manutencao["data_abertura"])
                                    );
                                    ?>
                                </div>
                            </div>

                            <span class="badge bg-<?php echo $cor; ?>">
                                <?php echo htmlspecialchars($manutencao["status"]); ?>
                            </span>

                        </div>
                    </a>

                <?php endwhile; ?>

            </div>
<div class="card shadow-sm border-0 mt-4">

    <div class="card-body">

        <div class="d-flex justify-content-between align-items-center mb-3">

            <h5 class="mb-0">
                ⚠ Equipamentos em manutenção
            </h5>

            <a
                href="paginas/listar.php?status=Manutenção"
                class="btn btn-sm btn-outline-warning"
            >
                Ver todos
            </a>

        </div>

        <?php if($equipamentosManutencao->num_rows > 0): ?>

            <div class="list-group list-group-flush">

                <?php while($equipamento = $equipamentosManutencao->fetch_assoc()): ?>

                    <?php

                    $dias = floor(
                        (time() - strtotime($equipamento["data_abertura"]))
                        / 86400
                    );

                    ?>

                    <a
                        href="paginas/visualizar_manutencao.php?id=<?php echo $equipamento["manutencao_id"]; ?>"
                        class="list-group-item list-group-item-action"
                    >

                        <strong>

                            <?php
                            echo str_pad(
                                $equipamento["id"],
                                4,
                                "0",
                                STR_PAD_LEFT
                            );
                            ?>

                            -

                            <?php
                            echo htmlspecialchars($equipamento["nome"]);
                            ?>

                        </strong>

                        <div class="small text-muted mt-1">

                            <?php
                            echo htmlspecialchars($equipamento["tipo"]);
                            ?>

                            •

                            há

                            <strong>

                                <?php echo $dias; ?>

                            </strong>

                            dia<?php echo $dias == 1 ? "" : "s"; ?>

                        </div>

                    </a>

                <?php endwhile; ?>

            </div>

        <?php else: ?>

            <div class="text-center text-muted py-4">

                🎉 Nenhum equipamento está em manutenção.

            </div>

        <?php endif; ?>

    </div>

</div>
        <?php else: ?>

            <p class="text-muted mb-0">
                Nenhuma manutenção cadastrada.
            </p>

        <?php endif; ?>

    </div>
</div>

<?php include "includes/footer.php"; ?>