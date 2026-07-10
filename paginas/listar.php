<?php
include "../config/protege.php";
include "../config/conexao.php";

$pesquisa = "";

if (isset($_GET["pesquisa"])) {
    $pesquisa = $_GET["pesquisa"];
}

if (!empty($pesquisa)) {
    $sql = "SELECT * FROM equipamentos 
            WHERE id LIKE ?
            OR nome LIKE ?
            OR fabricante LIKE ?
            OR modelo LIKE ?
            OR numero_serie LIKE ?
            OR setor LIKE ?
            ORDER BY id DESC";

    $termo = "%" . $pesquisa . "%";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $termo, $termo, $termo, $termo, $termo, $termo);
    $stmt->execute();

    $resultado = $stmt->get_result();
} else {
    $sql = "SELECT * FROM equipamentos ORDER BY id DESC";
    $resultado = $conn->query($sql);
}

include "../includes/header.php";
include "../includes/sidebar.php";
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2>Equipamentos</h2>
        <p class="text-muted">Lista de equipamentos cadastrados no inventário.</p>
    </div>

    <a href="cadastrar.php" class="btn btn-primary">
        + Novo Equipamento
    </a>
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
        <form method="GET" class="row g-2">
            <div class="col-md-10">
                <input 
                    type="text" 
                    name="pesquisa" 
                    class="form-control"
                    placeholder="Pesquisar por patrimônio, nome, marca, modelo, número de série ou setor"
                    value="<?php echo htmlspecialchars($pesquisa); ?>"
                >
            </div>

            <div class="col-md-2">
                <button class="btn btn-primary w-100" type="submit">
                    Pesquisar
                </button>
            </div>
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
                                <a href="visualizar.php?id=<?php echo $equipamento['id']; ?>" class="btn btn-sm btn-info text-white">Ver</a>

                                <a href="editar.php?id=<?php echo $equipamento['id']; ?>" class="btn btn-sm btn-warning">Editar</a>

                                <a href="../excluir.php?id=<?php echo $equipamento['id']; ?>" 
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('Tem certeza que deseja remover este equipamento?');">
                                   Remover
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center text-muted">
                            Nenhum equipamento encontrado.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

    </div>
</div>

<?php include "../includes/footer.php"; ?>