<?php
include "../config/protege.php";
include "../config/conexao.php";

$sql = "
    SELECT
        manutencoes.id,
        manutencoes.tipo,
        manutencoes.status,
        manutencoes.data_abertura,
        manutencoes.numero_os,
        equipamentos.nome AS equipamento_nome,
        equipamentos.id AS equipamento_id,
        empresas.nome AS empresa_nome
    FROM manutencoes
    INNER JOIN equipamentos
        ON manutencoes.equipamento_id = equipamentos.id
    INNER JOIN empresas
        ON manutencoes.empresa_id = empresas.id
    ORDER BY manutencoes.id DESC
";

$resultado = $conn->query($sql);

include "../includes/header.php";
include "../includes/sidebar.php";
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2>Manutenções</h2>
        <p class="text-muted">
            Consulte as manutenções registradas no sistema.
        </p>
    </div>

    <a href="nova_manutencao.php" class="btn btn-primary">
        + Nova Manutenção
    </a>
</div>

<?php if (isset($_GET["sucesso"])): ?>
    <div class="alert alert-success">
        Manutenção cadastrada com sucesso.
    </div>
<?php endif; ?>

<div class="card shadow-sm border-0">
    <div class="card-body">

        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>Patrimônio</th>
                    <th>Equipamento</th>
                    <th>Empresa</th>
                    <th>Tipo</th>
                    <th>Status</th>
                    <th>Abertura</th>
                    <th>OS</th>
                    <th>Ações</th>
                </tr>
            </thead>

            <tbody>
                <?php if ($resultado->num_rows > 0): ?>

                    <?php while ($manutencao = $resultado->fetch_assoc()): ?>
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
                                    $manutencao["empresa_nome"]
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

                                <span class="badge bg-<?php echo $cor; ?>">
                                    <?php
                                    echo htmlspecialchars(
                                        $manutencao["status"]
                                    );
                                    ?>
                                </span>
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
                                echo htmlspecialchars(
                                    $manutencao["numero_os"] ?: "-"
                                );
                                ?>
                            </td>

                            <td>
                                <a
                                    href="visualizar_manutencao.php?id=<?php echo $manutencao['id']; ?>"
                                    class="btn btn-sm btn-info text-white"
                                >
                                    Ver
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>

                <?php else: ?>

                    <tr>
                        <td colspan="8" class="text-center text-muted">
                            Nenhuma manutenção cadastrada.
                        </td>
                    </tr>

                <?php endif; ?>
            </tbody>
        </table>

    </div>
</div>

<?php include "../includes/footer.php"; ?>