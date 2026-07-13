<?php
include "../config/protege.php";
include "../config/conexao.php";

$sqlEquipamentos = "
    SELECT id, nome, fabricante, modelo
    FROM equipamentos
    ORDER BY nome
";
$equipamentos = $conn->query($sqlEquipamentos);

$sqlEmpresas = "
    SELECT id, nome
    FROM empresas
    ORDER BY nome
";
$empresas = $conn->query($sqlEmpresas);

include "../includes/header.php";
include "../includes/sidebar.php";
?>

<div class="mb-4">
    <h2>Nova Manutenção</h2>
    <p class="text-muted">
        Registre uma manutenção para um equipamento clínico.
    </p>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">

        <form action="../salvar_manutencao.php" method="POST">

            <div class="row">

                <div class="col-md-6 mb-3">
                    <label class="form-label">Equipamento *</label>

                    <select name="equipamento_id" class="form-select" required>
                        <option value="">Selecione o equipamento</option>

                        <?php while ($equipamento = $equipamentos->fetch_assoc()): ?>
                            <option value="<?php echo $equipamento["id"]; ?>">
                                <?php
                                echo str_pad(
                                    $equipamento["id"],
                                    4,
                                    "0",
                                    STR_PAD_LEFT
                                );

                                echo " - ";
                                echo htmlspecialchars($equipamento["nome"]);
                                echo " - ";
                                echo htmlspecialchars($equipamento["fabricante"]);
                                echo " ";
                                echo htmlspecialchars($equipamento["modelo"]);
                                ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Empresa responsável *</label>

                    <select name="empresa_id" class="form-select" required>
                        <option value="">Selecione a empresa</option>

                        <?php while ($empresa = $empresas->fetch_assoc()): ?>
                            <option value="<?php echo $empresa["id"]; ?>">
                                <?php echo htmlspecialchars($empresa["nome"]); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Tipo *</label>

                    <select name="tipo" class="form-select" required>
                        <option value="Corretiva">Corretiva</option>
                        <option value="Preventiva">Preventiva</option>
                        <option value="Calibração">Calibração</option>
                        <option value="Inspeção">Inspeção</option>
                        <option value="Outro">Outro</option>
                    </select>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Status *</label>

                    <select name="status" class="form-select" required>
                        <option value="Aberta">Aberta</option>
                        <option value="Em andamento">Em andamento</option>
                    </select>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Data de abertura *</label>

                    <input
                        type="date"
                        name="data_abertura"
                        class="form-control"
                        value="<?php echo date("Y-m-d"); ?>"
                        required
                    >
                </div>

                <div class="col-md-12 mb-3">
                    <label class="form-label">Descrição do problema *</label>

                    <textarea
                        name="descricao_problema"
                        class="form-control"
                        rows="5"
                        required
                    ></textarea>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Número da OS</label>

                    <input
                        type="text"
                        name="numero_os"
                        class="form-control"
                    >
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Valor</label>

                    <input
                        type="number"
                        name="valor"
                        class="form-control"
                        min="0"
                        step="0.01"
                        placeholder="0,00"
                    >
                </div>

                <div class="col-md-12 mb-3">
                    <label class="form-label">Observações</label>

                    <textarea
                        name="observacoes"
                        class="form-control"
                        rows="3"
                    ></textarea>
                </div>

            </div>

            <button type="submit" class="btn btn-primary">
                Salvar manutenção
            </button>

            <a href="listar.php" class="btn btn-secondary">
                Cancelar
            </a>

        </form>

    </div>
</div>

<?php include "../includes/footer.php"; ?>