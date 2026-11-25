<?php
session_start();

include "cons.php";
require_once "DLL.php";

$consulta = "SELECT * FROM produtos";
$resultado = banco($server, $user, $password, $db, $consulta);

// Conta quantos itens (somando as quantidades) o usuário logado tem no carrinho
$total_carrinho = 0;
if (isset($_SESSION["login"])) {
    $usuario_id = (int) $_SESSION["UsuarioId"];
    $consultaCarrinho = "SELECT SUM(quantidade) AS total FROM carrinho WHERE usuario_id = $usuario_id";
    $resultadoCarrinho = banco($server, $user, $password, $db, $consultaCarrinho);
    $linhaCarrinho = $resultadoCarrinho->fetch_assoc();
    $total_carrinho = $linhaCarrinho["total"] ?? 0;
}
?>

<html>

<head>
    <meta charset="UTF-8">
    <title>Amisadora</title>
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

        <a href="logout.php">
            Sair (<?= $_SESSION["login"] ?>)
        </a>

    <?php else: ?>

        <a href="login.php">Login</a>

    <?php endif; ?>

    <a href="contato.php">Contato</a>
    <a href="carrinho.php">Carrinho (<?= $total_carrinho ?>)</a>

</nav>

<main class="vitrine">

    <section class="produtos">

        <?php while ($linha = $resultado->fetch_assoc()): ?>

            <div class="produto">

                <img
                    src="img/<?= $linha["Imagem"] ?>"
                    alt="<?= $linha["Nome"] ?>"
                >

                <h3>
                    <?= $linha["Nome"] ?>
                </h3>

                <p>
                    R$ <?= number_format($linha["Preco"], 2, ',', '.') ?>
                </p>

                <form action="adicionar.php" method="POST">
                    <input type="hidden" name="produto_id" value="<?= (int) $linha["Id"] ?>">
                    <button type="submit">
                        Adicionar ao Carrinho
                    </button>
                </form>

            </div>

        <?php endwhile; ?>

    </section>

</main>

<?php include "footer.php"; ?>

</body>
</html>
