<?php
require_once "../config/protege.php";
require_once "../config/conexao.php";

$sql = "
    SELECT
        id,
        nome,
        descricao,
        criado_em
    FROM acessorios
    ORDER BY nome ASC
";

$resultado = $conn->query($sql);

if (!$resultado) {
    die("Erro ao buscar os acessórios: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Acessórios - MedInventory</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
</head>

<body class="bg-light">

<?php require_once "../includes/header.php"; ?>

<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h1 class="h3 mb-1">
                Acessórios
            </h1>

            <p class="text-muted mb-0">
                Cadastre os acessórios utilizados pelos equipamentos clínicos.
            </p>
        </div>

        <a
            href="cadastrar_acessorio.php"
            class="btn btn-primary"
        >
            + Novo acessório
        </a>

    </div>

    <?php if (isset($_GET["sucesso"])): ?>

        <div class="alert alert-success alert-dismissible fade show">

            <?php
            $mensagens = [
                "cadastrado" => "Acessório cadastrado com sucesso.",
                "atualizado" => "Acessório atualizado com sucesso.",
                "excluido" => "Acessório excluído com sucesso."
            ];

            echo $mensagens[$_GET["sucesso"]]
                ?? "Operação realizada com sucesso.";
            ?>

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    <?php endif; ?>

    <?php if (isset($_GET["erro"])): ?>

        <div class="alert alert-danger alert-dismissible fade show">

            <?php
            $mensagensErro = [
                "nao_encontrado" => "Acessório não encontrado.",
                "vinculado" => "Este acessório está vinculado a um equipamento e não pode ser excluído.",
                "exclusao" => "Não foi possível excluir o acessório."
            ];

            echo $mensagensErro[$_GET["erro"]]
                ?? "Ocorreu um erro durante a operação.";
            ?>

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    <?php endif; ?>

    <div class="card shadow-sm">

        <div class="card-body">

            <?php if ($resultado->num_rows === 0): ?>

                <div class="text-center py-5">

                    <p class="text-muted mb-3">
                        Nenhum acessório cadastrado.
                    </p>

                    <a
                        href="cadastrar_acessorio.php"
                        class="btn btn-primary"
                    >
                        Cadastrar primeiro acessório
                    </a>

                </div>

            <?php else: ?>

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Descrição</th>
                                <th class="text-end">Ações</th>
                            </tr>
                        </thead>

                        <tbody>

                        <?php while ($acessorio = $resultado->fetch_assoc()): ?>

                            <tr>

                                <td>
                                    <strong>
                                        <?= htmlspecialchars(
                                            $acessorio["nome"]
                                        ) ?>
                                    </strong>
                                </td>

                                <td>
                                    <?= htmlspecialchars(
                                        $acessorio["descricao"] ?: "-"
                                    ) ?>
                                </td>

                                <td class="text-end">

                                    <a
                                        href="editar_acessorio.php?id=<?= (int) $acessorio["id"] ?>"
                                        class="btn btn-sm btn-outline-primary"
                                    >
                                        Editar
                                    </a>

                                    <a
                                        href="excluir_acessorio.php?id=<?= (int) $acessorio["id"] ?>"
                                        class="btn btn-sm btn-outline-danger"
                                        onclick="return confirm('Deseja realmente excluir este acessório?');"
                                    >
                                        Excluir
                                    </a>

                                </td>

                            </tr>

                        <?php endwhile; ?>

                        </tbody>

                    </table>

                </div>

            <?php endif; ?>

        </div>

    </div>

</div>

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>

</body>
</html>