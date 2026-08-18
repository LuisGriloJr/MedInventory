<?php
session_start();

include "config/conexao.php";

$erro = "";

$usuarioLogin = trim($_POST["usuario"] ?? "");
$senha = $_POST["senha"] ?? "";

$sql = "SELECT * FROM usuarios WHERE usuario = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $usuarioLogin);
$stmt->execute();

$resultado = $stmt->get_result();
$usuario = $resultado->fetch_assoc();

if ($usuario && password_verify($senha, $usuario["senha"])) {

    $_SESSION["usuario_id"] = $usuario["id"];
    $_SESSION["usuario_nome"] = $usuario["nome"];
    $_SESSION["usuario_nivel"] = $usuario["nivel"];

    header("Location: index.php");
    exit;
}

$erro = "Usuário ou senha inválidos.";
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login - Inventário Clínico</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="container">
    <h1>Login</h1>
    <p>Acesse o sistema de inventário clínico.</p>

    <?php if (!empty($erro)): ?>
        <p class="erro"><?php echo $erro; ?></p>
    <?php endif; ?>

    <form method="POST" class="formulario">
        <label>Usuário</label>
        <input
            type="text"
        name="usuario"
        class="form-control"
        required
        autocomplete="username"
        >

        <label>Senha</label>
        <input type="password" name="senha" required>

        <div class="botoes">
            <button type="submit" class="btn">Entrar</button>
        </div>
    </form>
</div>

</body>
</html>