<?php
require_once "../config/protege.php";
require_once "../includes/header.php";
?>

<div class="container py-4">

    <div class="mb-4">
        <h1 class="h3 mb-1">Cadastrar Acessório</h1>
        <p class="text-muted mb-0">
            Cadastre um acessório que poderá ser vinculado aos equipamentos clínicos.
        </p>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">

            <form action="salvar_acessorio.php" method="POST">

                <div class="mb-3">
                    <label class="form-label">
                        Nome do acessório *
                    </label>

                    <input
                        type="text"
                        name="nome"
                        class="form-control"
                        placeholder="Ex.: Cabo ECG 5 vias"
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
                        placeholder="Ex.: Cabo compatível com monitor multiparamétrico..."
                    ></textarea>
                </div>

                <div class="d-flex gap-2">
                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        Salvar
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