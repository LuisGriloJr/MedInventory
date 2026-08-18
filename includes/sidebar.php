<aside class="sidebar">

    <a href="/inventario-clinico/index.php">
        🏠 Dashboard
    </a>

    <a href="/inventario-clinico/paginas/listar.php">
        📦 Equipamentos
    </a>

    <?php if ($_SESSION["usuario_nivel"] === "admin"): ?>

        <a href="/inventario-clinico/paginas/cadastrar.php">
            ➕ Novo Equipamento
        </a>

        <a href="/inventario-clinico/paginas/acessorios.php">
            🔌 Acessórios
        </a>

        <a href="/inventario-clinico/paginas/empresas.php">
            🏢 Empresas de Manutenção
        </a>

    <?php endif; ?>

    <a href="/inventario-clinico/paginas/listar_manutencoes.php">
        🛠️ Manutenções
    </a>

    <a href="/inventario-clinico/paginas/relatorios.php">
        📊 Relatórios
    </a>

</aside>

<main class="conteudo">