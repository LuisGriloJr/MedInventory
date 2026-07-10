<?php
include "config/protege.php";
include "config/conexao.php";

$sql = "SELECT COUNT(*) AS total FROM equipamentos";
$resultado = $conn->query($sql);
$total = $resultado->fetch_assoc()["total"];

$sqlUso = "SELECT COUNT(*) AS total FROM equipamentos WHERE status = 'Em uso'";
$resUso = $conn->query($sqlUso);
$totalUso = $resUso->fetch_assoc()["total"];

$sqlManutencao = "SELECT COUNT(*) AS total FROM equipamentos WHERE status = 'Manutenção'";
$resManutencao = $conn->query($sqlManutencao);
$totalManutencao = $resManutencao->fetch_assoc()["total"];

include "includes/header.php";
include "includes/sidebar.php";
?>

<div class="mb-4">
    <h2>Dashboard</h2>
    <p class="text-muted">
        Bem-vindo ao MedInventory, <?php echo $_SESSION["usuario_nome"]; ?>.
    </p>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h6 class="text-muted">Total de equipamentos</h6>
                <h1><?php echo $total; ?></h1>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h6 class="text-muted">Em uso</h6>
                <h1><?php echo $totalUso; ?></h1>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h6 class="text-muted">Em manutenção</h6>
                <h1><?php echo $totalManutencao; ?></h1>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <h5>Ações rápidas</h5>

        <a href="paginas/cadastrar.php" class="btn btn-primary">
            + Novo Equipamento
        </a>

        <a href="paginas/listar.php" class="btn btn-secondary">
            Listar Equipamentos
        </a>
    </div>
</div>

<?php include "includes/footer.php"; ?>