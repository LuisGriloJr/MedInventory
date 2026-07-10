<?php include "../config/protege.php"; ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Equipamento</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<div class="container">
    <h1>Cadastrar Equipamento</h1>
    <p>Preencha os dados do equipamento clínico.</p>

    <form action="../salvar.php" method="POST" enctype="multipart/form-data" class="formulario">

        

        <label>Nome do equipamento *</label>
        <input type="text" name="nome" required>

        

        <label>Fabricante</label>
        <input type="text" name="fabricante">

        <label>Modelo</label>
        <input type="text" name="modelo">

        <label>Número de série</label>
        <input type="text" name="numero_serie">

        <label>Setor</label>
        <input type="text" name="setor">

        <label>Localização</label>
        <input type="text" name="localizacao">

        <label>Status</label>
        <select name="status">
            <option value="Em uso">Em uso</option>
            <option value="Manutenção">Manutenção</option>
            <option value="Baixado">Baixado</option>
            <option value="Estoque">Estoque</option>
        </select>

        <label>Foto do equipamento</label>
        <input type="file" name="foto" accept="image/*">

        <label>Observações</label>
        <textarea name="observacoes" rows="4"></textarea>

        <div class="botoes">
            <button type="submit" class="btn">Salvar</button>
            <a href="../index.php" class="btn secundario">Voltar</a>
        </div>

    </form>
</div>

</body>
</html>