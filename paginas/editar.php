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

<h2>Editar Equipamento</h2>
<p class="text-muted">Patrimônio interno: <?php echo str_pad($equipamento['id'], 4, '0', STR_PAD_LEFT); ?></p>

<div class="card shadow-sm border-0">
    <div class="card-body">

        <form action="../atualizar.php" method="POST" enctype="multipart/form-data">

            <input type="hidden" name="id" value="<?php echo $equipamento['id']; ?>">

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nome do equipamento</label>
                    <input type="text" name="nome" class="form-control" value="<?php echo htmlspecialchars($equipamento['nome']); ?>" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Fabricante</label>
                    <input type="text" name="fabricante" class="form-control" value="<?php echo htmlspecialchars($equipamento['fabricante']); ?>">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Modelo</label>
                    <input type="text" name="modelo" class="form-control" value="<?php echo htmlspecialchars($equipamento['modelo']); ?>">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Número de série</label>
                    <input type="text" name="numero_serie" class="form-control" value="<?php echo htmlspecialchars($equipamento['numero_serie']); ?>">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Setor</label>
                    <input type="text" name="setor" class="form-control" value="<?php echo htmlspecialchars($equipamento['setor']); ?>">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Localização</label>
                    <input type="text" name="localizacao" class="form-control" value="<?php echo htmlspecialchars($equipamento['localizacao']); ?>">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="Em uso" <?php if ($equipamento['status'] == "Em uso") echo "selected"; ?>>Em uso</option>
                        <option value="Manutenção" <?php if ($equipamento['status'] == "Manutenção") echo "selected"; ?>>Manutenção</option>
                        <option value="Estoque" <?php if ($equipamento['status'] == "Estoque") echo "selected"; ?>>Estoque</option>
                        <option value="Baixado" <?php if ($equipamento['status'] == "Baixado") echo "selected"; ?>>Baixado</option>
                        <option value="Desativado" <?php if ($equipamento['status'] == "Desativado") echo "selected"; ?>>Desativado</option>
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Trocar foto</label>
                    <input type="file" name="foto" class="form-control" accept="image/*">
                </div>

                <div class="col-md-12 mb-3">
                    <label class="form-label">Foto atual</label><br>

                    <?php if (!empty($equipamento['foto'])): ?>
                        <img src="../<?php echo $equipamento['foto']; ?>" style="width:120px; height:120px; object-fit:cover; border-radius:8px;">
                    <?php else: ?>
                        <p class="text-muted">Sem foto</p>
                    <?php endif; ?>
                </div>

                <div class="col-md-12 mb-3">
                    <label class="form-label">Observações</label>
                    <textarea name="observacoes" class="form-control" rows="4"><?php echo htmlspecialchars($equipamento['observacoes']); ?></textarea>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Salvar Alterações</button>
            <a href="listar.php" class="btn btn-secondary">Voltar</a>

        </form>

    </div>
</div>

<?php include "../includes/footer.php"; ?>