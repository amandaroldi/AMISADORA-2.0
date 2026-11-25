<?php
session_start();

include "cons.php";
require_once "DLL.php";

if (!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit;
}

$login = $_SESSION["login"];
$usuario = addslashes($login);
$usuario_id = (int) $_SESSION["UsuarioId"];

$consulta = "SELECT produtos.Nome, produtos.Preco, carrinho.quantidade
             FROM carrinho
             JOIN produtos ON carrinho.produto_id = produtos.Id
             WHERE carrinho.usuario_id = $usuario_id";
$resultado = banco($server, $user, $password, $db, $consulta);

$carrinho = [];
while ($linha = $resultado->fetch_assoc()) {
    $carrinho[] = $linha;
}

$total = 0;

foreach ($carrinho as $item) {
    $total += $item["Preco"] * $item["quantidade"];
}

$pagamento = addslashes($_SESSION["pagamento"] ?? "Cartão");
$dataHora = date("Y-m-d H:i:s");

$consulta = "INSERT INTO vendas (Usuario, Total, Pagamento, DataHora) VALUES ('$usuario', '$total', '$pagamento', '$dataHora')";

$resultado = banco($server, $user, $password, $db, $consulta);

foreach ($carrinho as $item) {

    $produto = addslashes($item["Nome"]);
    $subtotal = $item["Preco"] * $item["quantidade"];

    $consulta = "INSERT INTO itens_venda (Usuario, Produto, Preco) VALUES ('$usuario', '$produto', '$subtotal')";

    $resultado = banco($server, $user, $password, $db, $consulta);
}

$consulta = "DELETE FROM carrinho WHERE usuario_id = $usuario_id";
$resultado = banco($server, $user, $password, $db, $consulta);
?>

<html>
<head>
    <meta charset="UTF-8">
    <title>Compra Finalizada</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
</head>

<body>

<header class="header">
    <img src="header.png" alt="Header da Loja">
</header>

<main class="confirmacao-container">

    <div class="confirmacao-box">

        <h2>Compra Finalizada</h2>

        <div class="info">

            <p><strong>Usuário:</strong> <?= htmlspecialchars($login) ?></p>
            <p><strong>Forma de pagamento:</strong> <?= htmlspecialchars($pagamento) ?></p>

            <hr>

            <?php foreach ($carrinho as $item): ?>
                <p>
                    <?= htmlspecialchars($item["Nome"]) ?>
                    (x<?= $item["quantidade"] ?>)
                    - R$ <?= number_format($item["Preco"] * $item["quantidade"], 2, ',', '.') ?>
                </p>
            <?php endforeach; ?>

            <hr>

            <p><strong>Total pago: R$ <?= number_format($total, 2, ',', '.') ?></strong></p>

        </div>

        <p>Obrigado pela sua compra!</p>

        <br>

        <a href="index.php" style="text-decoration: none;">
            <button>Voltar para a Loja</button>
        </a>

    </div>

</main>

<?php include "footer.php"; ?>

</body>
</html>