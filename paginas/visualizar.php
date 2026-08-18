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

$urlEquipamento = "https://cafedatarde.online/inventario-clinico/paginas/visualizar.php?id="
    . $equipamento["id"];

$urlQr = "https://api.qrserver.com/v1/create-qr-code/?size=180x180&data="
    . urlencode($urlEquipamento);

$sqlManutencoes = "
    SELECT
        manutencoes.id,
        manutencoes.tipo,
        manutencoes.status,
        manutencoes.data_abertura,
        manutencoes.data_conclusao,
        manutencoes.numero_os,
        empresas.nome AS empresa_nome
    FROM manutencoes
    INNER JOIN empresas
        ON empresas.id = manutencoes.empresa_id
    WHERE manutencoes.equipamento_id = ?
    ORDER BY manutencoes.data_abertura DESC, manutencoes.id DESC
";

$stmtManutencoes = $conn->prepare($sqlManutencoes);
$stmtManutencoes->bind_param("i", $id);
$stmtManutencoes->execute();

$manutencoes = $stmtManutencoes->get_result();

$sqlAcessorios = "
    SELECT
        acessorios.id,
        acessorios.nome,
        acessorios.descricao
    FROM equipamentos_acessorios
    INNER JOIN acessorios
        ON acessorios.id = equipamentos_acessorios.acessorio_id
    WHERE equipamentos_acessorios.equipamento_id = ?
    ORDER BY acessorios.nome ASC
";



$stmtAcessorios = $conn->prepare($sqlAcessorios);
$stmtAcessorios->bind_param("i", $id);
$stmtAcessorios->execute();

$acessorios = $stmtAcessorios->get_result();

$sqlTodosAcessorios = "
    SELECT
        acessorios.id,
        acessorios.nome
    FROM acessorios
    WHERE acessorios.id NOT IN (
        SELECT acessorio_id
        FROM equipamentos_acessorios
        WHERE equipamento_id = ?
    )
    ORDER BY acessorios.nome ASC
";

$stmtTodosAcessorios = $conn->prepare($sqlTodosAcessorios);
$stmtTodosAcessorios->bind_param("i", $id);
$stmtTodosAcessorios->execute();

$todosAcessorios = $stmtTodosAcessorios->get_result();

include "../includes/header.php";
include "../includes/sidebar.php";
?>

<div class="card shadow-sm border-0">
    <div class="card-body">

    <div class="row">

        <div class="col-md-4 text-center">

            <?php if (!empty($equipamento['foto'])): ?>

                <a href="../<?php echo $equipamento['foto']; ?>" target="_blank">
                    <img
                        src="../<?php echo $equipamento['foto']; ?>"
                        class="img-fluid rounded shadow-sm"
                        style="max-height:320px; object-fit:contain;">
                </a>

                <p class="text-muted mt-2">
                    Clique na imagem para ampliar
                </p>

            <?php else: ?>

                <div class="border rounded p-5 text-muted">
                    Sem foto
                </div>

            <?php endif; ?>

        </div>

        <div class="col-md-8">

            <div class="d-flex justify-content-between align-items-start">

                <div>
                    <h2>
                        <?php
                        echo htmlspecialchars(
                            mb_convert_case($equipamento['nome'], MB_CASE_TITLE, "UTF-8")
                        );
                         ?>
                    </h2>

                    <h5 class="text-primary">
                        Patrimônio <?php echo str_pad($equipamento['id'], 4, '0', STR_PAD_LEFT); ?>
                    </h5>

                    <a
    href="imprimir_etiqueta.php?id=<?php echo $equipamento["id"]; ?>"
    class="btn btn-sm btn-outline-dark mt-2"
>
    Imprimir etiqueta
</a>

                    
                </div>

                <?php
                    $cor = "secondary";

                    if($equipamento['status']=="Em uso") $cor="success";
                    if($equipamento['status']=="Manutenção") $cor="warning";
                    if($equipamento['status']=="Baixado") $cor="danger";
                    if($equipamento['status']=="Desativado") $cor="dark";
                ?>

                <span class="badge bg-<?php echo $cor; ?> fs-6">
                    <?php echo htmlspecialchars($equipamento['status']); ?>
                </span>

            </div>

            <hr>

            <div class="row">

                <div class="col-md-6 mb-3">
                    <strong>Fabricante</strong><br>
                    <?php echo htmlspecialchars($equipamento['fabricante']); ?>
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Modelo</strong><br>
                    <?php echo htmlspecialchars($equipamento['modelo']); ?>
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Número de Série</strong><br>
                    <?php echo htmlspecialchars($equipamento['numero_serie']); ?>
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Setor</strong><br>
                    <?php echo htmlspecialchars($equipamento['setor']); ?>
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Localização</strong><br>
                    <?php echo htmlspecialchars($equipamento['localizacao']); ?>
                </div>

            </div>

            <hr>

            <strong>Observações</strong>

            <div class="border rounded p-3 mt-2 bg-light">

                <?php

                if(trim($equipamento['observacoes'])=="")
                    echo "<span class='text-muted'>Nenhuma observação cadastrada.</span>";
                else
                    echo nl2br(htmlspecialchars($equipamento['observacoes']));

                ?>

            </div>

            <hr class="mt-4">

<hr class="mt-4">

<div class="d-flex justify-content-between align-items-center mb-3">

    <h4 class="mb-0">
        Acessórios
    </h4>

    <button
        type="button"
        class="btn btn-sm btn-primary"
        data-bs-toggle="modal"
        data-bs-target="#modalAdicionarAcessorio"
    >
        + Adicionar acessório
    </button>

</div>

<?php if ($acessorios->num_rows > 0): ?>

    <div class="list-group mb-4">

        <?php while ($acessorio = $acessorios->fetch_assoc()): ?>

            <div class="list-group-item d-flex justify-content-between align-items-center">

                <div>

                    <strong>
                        <?php echo htmlspecialchars($acessorio["nome"]); ?>
                    </strong>

                    <?php if (!empty($acessorio["descricao"])): ?>

                        <div class="small text-muted mt-1">
                            <?php echo htmlspecialchars($acessorio["descricao"]); ?>
                        </div>

                    <?php endif; ?>

                </div>

                <a
                    href="remover_acessorio.php?equipamento_id=<?php echo (int) $equipamento["id"]; ?>&acessorio_id=<?php echo (int) $acessorio["id"]; ?>"
                    class="btn btn-sm btn-outline-danger"
                    onclick="return confirm('Deseja remover este acessório deste equipamento?');"
                >
                    Remover
                </a>

            </div>

        <?php endwhile; ?>

    </div>

<?php else: ?>

    <div class="border rounded p-3 text-muted mb-4">
        Nenhum acessório vinculado a este equipamento.
    </div>

<?php endif; ?>


<div
    class="modal fade"
    id="modalAdicionarAcessorio"
    tabindex="-1"
    aria-hidden="true"
>
    <div class="modal-dialog">

        <div class="modal-content">

            <form action="vincular_acessorio.php" method="POST">

                <div class="modal-header">

                    <h5 class="modal-title">
                        Adicionar acessório
                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                    ></button>

                </div>

                <div class="modal-body">

                    <input
                        type="hidden"
                        name="equipamento_id"
                        value="<?php echo (int) $equipamento["id"]; ?>"
                    >

                    <div class="mb-3">

                        <label class="form-label">
                            Acessório
                        </label>

                        <select
                            name="acessorio_id"
                            class="form-select"
                            required
                        >

                            <option value="">
                                Selecione...
                            </option>

                            <?php while ($item = $todosAcessorios->fetch_assoc()): ?>

                                <option value="<?php echo (int) $item["id"]; ?>">
                                    <?php echo htmlspecialchars($item["nome"]); ?>
                                </option>

                            <?php endwhile; ?>

                        </select>

                    </div>

                </div>

                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal"
                    >
                        Cancelar
                    </button>

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        Vincular
                    </button>

                </div>

            </form>

        </div>

    </div>
</div>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Histórico de Manutenções</h4>

    <a
        href="nova_manutencao.php?equipamento_id=<?php echo $equipamento['id']; ?>"
        class="btn btn-primary btn-sm"
    >
        + Nova manutenção
    </a>
</div>

<?php if ($manutencoes->num_rows > 0): ?>

    <div class="list-group">

        <?php while ($manutencao = $manutencoes->fetch_assoc()): ?>

            <?php
            $corManutencao = "secondary";

            if ($manutencao["status"] === "Aberta") {
                $corManutencao = "danger";
            }

            if ($manutencao["status"] === "Em andamento") {
                $corManutencao = "warning";
            }

            if ($manutencao["status"] === "Concluída") {
                $corManutencao = "success";
            }
            ?>

            <div class="list-group-item">

                <div class="d-flex justify-content-between align-items-start">

                    <div>
                        <h6 class="mb-1">
                            <?php echo htmlspecialchars($manutencao["tipo"]); ?>
                        </h6>

                        <p class="mb-1">
                            <strong>Empresa:</strong>
                            <?php echo htmlspecialchars($manutencao["empresa_nome"]); ?>
                        </p>

                        <small class="text-muted">
                            Abertura:
                            <?php
                            echo date(
                                "d/m/Y",
                                strtotime($manutencao["data_abertura"])
                            );
                            ?>

                            <?php if (!empty($manutencao["numero_os"])): ?>
                                | OS:
                                <?php echo htmlspecialchars($manutencao["numero_os"]); ?>
                            <?php endif; ?>
                        </small>
                    </div>

                    <div class="text-end">

                        <span class="badge bg-<?php echo $corManutencao; ?> mb-2">
                            <?php echo htmlspecialchars($manutencao["status"]); ?>
                        </span>

                        <br>

                        <a
                            href="visualizar_manutencao.php?id=<?php echo $manutencao['id']; ?>"
                            class="btn btn-sm btn-outline-primary"
                        >
                            Ver detalhes
                        </a>

                    </div>

                </div>

            </div>

        <?php endwhile; ?>

    </div>

<?php else: ?>

    <div class="border rounded p-4 text-center text-muted">
        Nenhuma manutenção registrada para este equipamento.
    </div>

<?php endif; ?>

            <div class="mt-4">

                <a href="editar.php?id=<?php echo $equipamento['id']; ?>" class="btn btn-warning">
                    Editar
                </a>

                <a href="listar.php" class="btn btn-secondary">
                    Voltar
                </a>

            </div>

        </div>

    </div>

</div>

<?php include "../includes/footer.php"; ?>