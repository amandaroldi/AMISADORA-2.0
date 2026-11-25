<?php
session_start();

include "cons.php";
require_once "DLL.php";

if (!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit;
}

$usuario_id = (int) $_SESSION["UsuarioId"];

$consulta = "SELECT carrinho.Id, carrinho.quantidade, produtos.Nome, produtos.Preco
             FROM carrinho
             JOIN produtos ON carrinho.produto_id = produtos.Id
             WHERE carrinho.usuario_id = $usuario_id";

$resultado = banco($server, $user, $password, $db, $consulta);

$itens = [];
while ($linha = $resultado->fetch_assoc()) {
    $itens[] = $linha;
}
?>

<html>
<head>
    <meta charset="UTF-8">
    <title>Carrinho</title>
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

<main class="carrinho-container">

    <h2>Seu Carrinho</h2>

    <?php if (empty($itens)): ?>

        <p>Seu carrinho está vazio.</p>

    <?php else: ?>

        <?php
        $total = 0;

        foreach ($itens as $item):

            $subtotal = $item["Preco"] * $item["quantidade"];
            $total += $subtotal;
        ?>

            <div class="item">
                <p>
                    <?= $item["Nome"] ?> - R$ <?= number_format($item["Preco"], 2, ',', '.') ?>
                    (Quantidade: <?= $item["quantidade"] ?>)
                    - Subtotal: R$ <?= number_format($subtotal, 2, ',', '.') ?>
                </p>

                <form action="remover.php" method="POST">
                    <input type="hidden" name="id" value="<?= $item['Id'] ?>">
                    <button type="submit">Remover</button>
                </form>
            </div>

        <?php endforeach; ?>

        <hr>

        <h3>Total: R$ <?= number_format($total, 2, ',', '.') ?></h3>

        <a href="confirmacao_compra.php">
            <button>Finalizar Compra</button>
        </a>

    <?php endif; ?>

</main>

<?php include "footer.php"; ?>

</body>
</html>
