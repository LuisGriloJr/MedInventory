<?php
require_once "../config/protege.php";
require_once "../config/conexao.php";
require_once "../includes/header.php";

$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if (!$id) {
    header("Location: acessorios.php?erro=nao_encontrado");
    exit;
}

$sql = "SELECT id, nome, descricao
        FROM acessorios
        WHERE id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();

$resultado = $stmt->get_result();
$acessorio = $resultado->fetch_assoc();

if (!$acessorio) {
    header("Location: acessorios.php?erro=nao_encontrado");
    exit;
}
?>

<div class="container py-4">

    <div class="mb-4">
        <h1 class="h3 mb-1">Editar Acessório</h1>

        <p class="text-muted mb-0">
            Altere as informações do acessório.
        </p>
    </div>

    <div class="card shadow-sm">

        <div class="card-body">

            <form action="atualizar_acessorio.php" method="POST">

                <input
                    type="hidden"
                    name="id"
                    value="<?= (int) $acessorio["id"] ?>"
                >

                <div class="mb-3">

                    <label class="form-label">
                        Nome do acessório *
                    </label>

                    <input
                        type="text"
                        name="nome"
                        class="form-control"
                        value="<?= htmlspecialchars($acessorio["nome"]) ?>"
                        required
                    >

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Descrição
                    </label>

                    <textarea
                        name="descricao"
                        class="form-control"
                        rows="4"
                    ><?= htmlspecialchars($acessorio["descricao"] ?? "") ?></textarea>

                </div>

                <div class="d-flex gap-2">

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        Salvar alterações
                    </button>

                    <a
                        href="acessorios.php"
                        class="btn btn-secondary"
                    >
                        Cancelar
                    </a>

                </div>

            </form>

        </div>

    </div>

</div>

<?php require_once "../includes/footer.php"; ?>