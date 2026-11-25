<?php
session_start();

if (!isset($_SESSION["UsuarioId"])) {
    header("Location: cadastro.php");
    exit;
}
?>

<html>
<head>
    <meta charset="UTF-8">
    <title>Cadastro - Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
</head>

<body>

<header class="header">
    <img src="header.png" alt="Header da Loja">
</header>

<nav class="menu">
    <a href="index.php">Início</a>
    <?php if (isset($_SESSION["login"])): ?>
        <a href="logout.php">Sair (<?= $_SESSION["login"] ?>)</a>
    <?php else: ?>
        <a href="login.php">Login</a>
    <?php endif; ?>
    <a href="contato.php">Contato</a>
    <a href="carrinho.php">Carrinho</a>
</nav>

<main class="cadastro-container">

    <div class="cadastro-box">

        <h2>Dados de Acesso</h2>

        <form action="banco.php" method="POST">

            <input type="text" name="login" placeholder="Login" required>
            <input type="password" name="senha" placeholder="Senha" required>

            <button type="submit" name="B2" value="1">Finalizar Cadastro</button>

        </form>

    </div>

</main>

<?php include "footer.php"; ?>

</body>
</html>