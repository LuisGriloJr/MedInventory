<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>MedInventory</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/inventario-clinico/assets/css/layout.css">
</head>

<body class="bg-light">

<nav class="navbar navbar-dark bg-primary">
    <div class="container-fluid">
        <a href="/inventario-clinico/index.php" class="navbar-brand">🏥 MedInventory</a>

        <div class="text-white">
            <?php echo $_SESSION["usuario_nome"]; ?>
            <a href="/inventario-clinico/logout.php" class="btn btn-sm btn-light ms-3">Sair</a>
        </div>
    </div>
</nav>

<div class="layout">