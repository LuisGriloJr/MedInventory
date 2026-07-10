<?php
include "../config/protege.php";

include "../includes/header.php";
include "../includes/sidebar.php";
?>

<div class="mb-4">
    <h2>Novo Equipamento</h2>
    <p class="text-muted">
        Cadastre um novo equipamento clínico no inventário.
    </p>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">

        <form action="../salvar.php" method="POST" enctype="multipart/form-data">

            <h5 class="mb-3">Identificação</h5>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nome do equipamento *</label>
                    <input
                        type="text"
                        name="nome"
                        class="form-control"
                        placeholder="Ex.: Bomba de infusão"
                        required
                    >
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Fabricante *</label>
                    <input
                        type="text"
                        name="fabricante"
                        class="form-control"
                        placeholder="Ex.: Mindray"
                        required
                    >
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Modelo *</label>
                    <input
                        type="text"
                        name="modelo"
                        class="form-control"
                        placeholder="Ex.: BC-5380"
                        required
                    >
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Número de série *</label>
                    <input
                        type="text"
                        name="numero_serie"
                        class="form-control"
                        required
                    >
                </div>
            </div>

            <hr>

            <h5 class="mb-3">Localização</h5>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Setor *</label>
                    <input
                        type="text"
                        name="setor"
                        class="form-control"
                        placeholder="Ex.: Centro Cirúrgico"
                        required
                    >
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Localização</label>
                    <input
                        type="text"
                        name="localizacao"
                        class="form-control"
                        placeholder="Ex.: Sala 2, bancada 1 ou leito 5"
                    >
                </div>
            </div>

            <hr>

            <h5 class="mb-3">Situação e documentação</h5>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Status *</label>
                    <select name="status" class="form-select" required>
                        <option value="Em uso">Em uso</option>
                        <option value="Manutenção">Manutenção</option>
                        <option value="Estoque">Estoque</option>
                        <option value="Baixado">Baixado</option>
                        <option value="Desativado">Desativado</option>
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Foto do equipamento</label>
                    <input
                        type="file"
                        name="foto"
                        class="form-control"
                        accept="image/*"
                    >
                </div>

                <div class="col-md-12 mb-3">
                    <label class="form-label">Observações</label>
                    <textarea
                        name="observacoes"
                        class="form-control"
                        rows="4"
                        placeholder="Informações adicionais sobre o equipamento"
                    ></textarea>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    Salvar Equipamento
                </button>

                <a href="listar.php" class="btn btn-secondary">
                    Cancelar
                </a>
            </div>

        </form>

    </div>
</div>

<?php include "../includes/footer.php"; ?>