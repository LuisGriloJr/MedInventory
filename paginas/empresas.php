<?php
include "../config/protege.php";
include "../config/conexao.php";

$sql = "SELECT * FROM empresas ORDER BY nome";
$resultado = $conn->query($sql);

include "../includes/header.php";
include "../includes/sidebar.php";
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2>Empresas de Manutenção</h2>
        <p class="text-muted">
            Cadastre as empresas responsáveis pelas manutenções dos equipamentos.
        </p>
    </div>
</div>

<?php if (isset($_GET["sucesso"])): ?>
    <div class="alert alert-success">
        Empresa cadastrada com sucesso.
    </div>
<?php endif; ?>

<div class="row g-4">
    <div class="col-md-5">
        <div class="card shadow-sm border-0">
            <div class="card-body">

                <h5 class="mb-3">Nova empresa</h5>

                <form action="../salvar_empresa.php" method="POST">

                    <div class="mb-3">
                        <label class="form-label">Nome da empresa *</label>
                        <input
                            type="text"
                            name="nome"
                            class="form-control"
                            required
                        >
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Contato</label>
                        <input
                            type="text"
                            name="contato"
                            class="form-control"
                        >
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Telefone</label>
                        <input
                            type="text"
                            name="telefone"
                            class="form-control"
                        >
                    </div>

                    <div class="mb-3">
                        <label class="form-label">E-mail</label>
                        <input
                            type="email"
                            name="email"
                            class="form-control"
                        >
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Observações</label>
                        <textarea
                            name="observacoes"
                            class="form-control"
                            rows="3"
                        ></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        Salvar empresa
                    </button>

                </form>

            </div>
        </div>
    </div>

    <div class="col-md-7">
        <div class="card shadow-sm border-0">
            <div class="card-body">

                <h5 class="mb-3">Empresas cadastradas</h5>

                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Contato</th>
                            <th>Telefone</th>
                            <th>E-mail</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if ($resultado->num_rows > 0): ?>
                            <?php while ($empresa = $resultado->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($empresa["nome"]); ?></td>
                                    <td><?php echo htmlspecialchars($empresa["contato"]); ?></td>
                                    <td><?php echo htmlspecialchars($empresa["telefone"]); ?></td>
                                    <td><?php echo htmlspecialchars($empresa["email"]); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center text-muted">
                                    Nenhuma empresa cadastrada.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>

<?php include "../includes/footer.php"; ?>