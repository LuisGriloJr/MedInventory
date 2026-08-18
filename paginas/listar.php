<?php
include "../config/protege.php";
include "../config/conexao.php";

$pesquisa = trim($_GET["pesquisa"] ?? "");
$statusFiltro = trim($_GET["status"] ?? "");
$setorFiltro = trim($_GET["setor"] ?? "");

$termo = "%" . $pesquisa . "%";

$sql = "
    SELECT
        equipamentos.*,
        manutencao_atual.tipo AS manutencao_tipo,
        manutencao_atual.empresa_nome AS manutencao_empresa
    FROM equipamentos

    LEFT JOIN (
        SELECT
            manutencoes.equipamento_id,
            manutencoes.tipo,
            empresas.nome AS empresa_nome
        FROM manutencoes

        INNER JOIN empresas
            ON empresas.id = manutencoes.empresa_id

        WHERE manutencoes.status IN ('Aberta', 'Em andamento')
    ) AS manutencao_atual
        ON manutencao_atual.equipamento_id = equipamentos.id

    WHERE
        (
            ? = ''
            OR CAST(equipamentos.id AS CHAR) LIKE ?
            OR equipamentos.nome LIKE ?
            OR equipamentos.fabricante LIKE ?
            OR equipamentos.modelo LIKE ?
            OR equipamentos.numero_serie LIKE ?
            OR equipamentos.setor LIKE ?
        )
        AND (? = '' OR equipamentos.status = ?)
        AND (? = '' OR equipamentos.setor = ?)

    ORDER BY equipamentos.id DESC
";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "sssssssssss",
    $pesquisa,
    $termo,
    $termo,
    $termo,
    $termo,
    $termo,
    $termo,
    $statusFiltro,
    $statusFiltro,
    $setorFiltro,
    $setorFiltro
);

$stmt->execute();
$resultado = $stmt->get_result();

$sqlSetores = "
    SELECT DISTINCT setor
    FROM equipamentos
    WHERE setor IS NOT NULL
      AND setor != ''
    ORDER BY setor
";

$setores = $conn->query($sqlSetores);

include "../includes/header.php";
include "../includes/sidebar.php";
?>



<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2>Equipamentos</h2>
        <p class="text-muted">Lista de equipamentos cadastrados no inventário.</p>
    </div>

    <?php if ($_SESSION["usuario_nivel"] === "admin"): ?>

    <a href="cadastrar.php" class="btn btn-primary">
        + Novo Equipamento
    </a>

<?php endif; ?>
</div>

<?php if (isset($_GET['sucesso'])): ?>
    <div class="alert alert-success">
        Equipamento cadastrado com sucesso!
    </div>
<?php endif; ?>

<?php if (isset($_GET['excluido'])): ?>
    <div class="alert alert-danger">
        Equipamento removido com sucesso!
    </div>
<?php endif; ?>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">

        <form method="GET" class="row g-3">

            <div class="col-md-5">
                <label class="form-label">Pesquisar</label>

                <input
                    type="text"
                    name="pesquisa"
                    class="form-control"
                    placeholder="Patrimônio, nome, fabricante, modelo ou série"
                    value="<?php echo htmlspecialchars($pesquisa); ?>"
                >
            </div>

            <div class="col-md-3">
                <label class="form-label">Status</label>

                <select name="status" class="form-select">
                    <option value="">Todos os status</option>

                    <option value="Em uso"
                        <?php if ($statusFiltro === "Em uso") echo "selected"; ?>>
                        Em uso
                    </option>

                    <option value="Manutenção"
                        <?php if ($statusFiltro === "Manutenção") echo "selected"; ?>>
                        Manutenção
                    </option>

                    <option value="Estoque"
                        <?php if ($statusFiltro === "Estoque") echo "selected"; ?>>
                        Estoque
                    </option>

                    <option value="Baixado"
                        <?php if ($statusFiltro === "Baixado") echo "selected"; ?>>
                        Baixado
                    </option>

                    <option value="Desativado"
                        <?php if ($statusFiltro === "Desativado") echo "selected"; ?>>
                        Desativado
                    </option>
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label">Setor</label>

                <select name="setor" class="form-select">
                    <option value="">Todos os setores</option>

                    <?php while ($setor = $setores->fetch_assoc()): ?>
                        <option
                            value="<?php echo htmlspecialchars($setor["setor"]); ?>"
                            <?php
                            if ($setorFiltro === $setor["setor"]) {
                                echo "selected";
                            }
                            ?>
                        >
                            <?php echo htmlspecialchars($setor["setor"]); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="col-md-1 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">
                    Filtrar
                </button>
            </div>

            <?php if (
                $pesquisa !== ""
                || $statusFiltro !== ""
                || $setorFiltro !== ""
            ): ?>
                <div class="col-12">
                    <a href="listar.php" class="btn btn-sm btn-outline-secondary">
                        Limpar filtros
                    </a>
                </div>
            <?php endif; ?>

        </form>

    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">

        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>Foto</th>
                    <th>Patrimônio</th>
                    <th>Equipamento</th>
                    <th>Fabricante</th>
                    <th>Modelo</th>
                    <th>Setor</th>
                    <th>Status</th>
                    <th>Manutenção Atual</th>
                    <th width="190">Ações</th>
                </tr>
            </thead>

            <tbody>
                <?php if ($resultado->num_rows > 0): ?>
                    <?php while ($equipamento = $resultado->fetch_assoc()): ?>
                        <tr>
                            <td>
                                <?php if (!empty($equipamento['foto'])): ?>
                                    <img src="../<?php echo $equipamento['foto']; ?>" style="width:60px; height:60px; object-fit:cover; border-radius:8px;">
                                <?php else: ?>
                                    <span class="text-muted">Sem foto</span>
                                <?php endif; ?>
                            </td>

                            <td><?php echo str_pad($equipamento['id'], 4, '0', STR_PAD_LEFT); ?></td>
                            <td><?php echo htmlspecialchars($equipamento['nome']); ?></td>
                            <td><?php echo htmlspecialchars($equipamento['fabricante']); ?></td>
                            <td><?php echo htmlspecialchars($equipamento['modelo']); ?></td>
                            <td><?php echo htmlspecialchars($equipamento['setor']); ?></td>

                            <td>
                                <?php if ($equipamento['status'] == "Em uso"): ?>
                                    <span class="badge bg-success">Em uso</span>
                                <?php elseif ($equipamento['status'] == "Manutenção"): ?>
                                    <span class="badge bg-warning text-dark">Manutenção</span>
                                <?php elseif ($equipamento['status'] == "Baixado"): ?>
                                    <span class="badge bg-danger">Baixado</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary"><?php echo htmlspecialchars($equipamento['status']); ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (!empty($equipamento["manutencao_tipo"])): ?>

                                    <strong>
                                        <?php echo htmlspecialchars($equipamento["manutencao_tipo"]); ?>
                                    </strong>

                                    <br>

                                    <small class="text-muted">
                                        <?php echo htmlspecialchars($equipamento["manutencao_empresa"]); ?>
                                    </small>

                                    <?php else: ?>

                                    <span class="text-muted">—</span>

                                <?php endif; ?>
                            </td>

                            <td>

    <!-- Todos os usuários podem visualizar -->
    <a href="visualizar.php?id=<?php echo $equipamento['id']; ?>"
       class="btn btn-sm btn-info text-white">
        Ver
    </a>

    <!-- Somente administrador pode editar ou remover -->
    <?php if ($_SESSION["usuario_nivel"] === "admin"): ?>

        <a href="editar.php?id=<?php echo $equipamento['id']; ?>"
           class="btn btn-sm btn-warning">
            Editar
        </a>

        <a href="../excluir.php?id=<?php echo $equipamento['id']; ?>"
           class="btn btn-sm btn-danger"
           onclick="return confirm('Tem certeza que deseja remover este equipamento?');">
            Remover
        </a>

    <?php endif; ?>

</td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9" class="text-center text-muted">
                            Nenhum equipamento encontrado.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

    </div>
</div>

<?php include "../includes/footer.php"; ?>