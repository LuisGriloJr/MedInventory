<?php
include "../config/protege.php";
include "../config/conexao.php";

$id = $_GET['id'];

$sql = "SELECT * FROM equipamentos WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();

$resultado = $stmt->get_result();
$equipamento = $resultado->fetch_assoc();

if (!$equipamento) {
    echo "Equipamento não encontrado.";
    exit;
}

include "../includes/header.php";
include "../includes/sidebar.php";
?>

<div class="mb-4">
    <a href="listar.php" class="btn btn-secondary btn-sm">Voltar</a>
    <a href="editar.php?id=<?php echo $equipamento['id']; ?>" class="btn btn-warning btn-sm">Editar</a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">

        <div class="row">
            <div class="col-md-4">
                <?php if (!empty($equipamento['foto'])): ?>
                    <img src="../<?php echo $equipamento['foto']; ?>" class="img-fluid rounded">
                <?php else: ?>
                    <div class="bg-secondary text-white p-5 text-center rounded">
                        Sem foto
                    </div>
                <?php endif; ?>
            </div>

            <div class="col-md-8">
                <h2><?php echo htmlspecialchars($equipamento['nome']); ?></h2>
                <p class="text-muted">
                    Patrimônio interno: <?php echo str_pad($equipamento['id'], 4, '0', STR_PAD_LEFT); ?>
                </p>

                <table class="table table-bordered">
                    <tr>
                        <th>Fabricante</th>
                        <td><?php echo htmlspecialchars($equipamento['fabricante']); ?></td>
                    </tr>
                    <tr>
                        <th>Modelo</th>
                        <td><?php echo htmlspecialchars($equipamento['modelo']); ?></td>
                    </tr>
                    <tr>
                        <th>Número de série</th>
                        <td><?php echo htmlspecialchars($equipamento['numero_serie']); ?></td>
                    </tr>
                    <tr>
                        <th>Setor</th>
                        <td><?php echo htmlspecialchars($equipamento['setor']); ?></td>
                    </tr>
                    <tr>
                        <th>Localização</th>
                        <td><?php echo htmlspecialchars($equipamento['localizacao']); ?></td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td><?php echo htmlspecialchars($equipamento['status']); ?></td>
                    </tr>
                    <tr>
                        <th>Observações</th>
                        <td><?php echo nl2br(htmlspecialchars($equipamento['observacoes'])); ?></td>
                    </tr>
                </table>
            </div>
        </div>

    </div>
</div>

<?php include "../includes/footer.php"; ?>