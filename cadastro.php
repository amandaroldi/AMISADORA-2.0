<?php
session_start();
?>

<html>
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Usuário</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<header class="header">
    <img src="header.png" alt="Header da Loja">
</header>

<main class="confirmacao-container">

    <div class="confirmacao-box">

        <h2>Cadastro - Dados Pessoais</h2>

        <form action="banco.php" method="POST">

            <input type="text" name="nome" placeholder="Nome completo" required>
            <input type="text" name="cpf" id="cpf" placeholder="CPF" required>
            <input type="text" name="endereco" placeholder="Endereço" required>
            <input type="text" name="bairro" placeholder="Bairro" required>
            <input type="text" name="cidade" placeholder="Cidade" required>
            <input type="text" name="estado" placeholder="Estado" required>
            <input type="text" name="cep" placeholder="CEP" required>

            <button type="submit" name="B1" value="1">Continuar</button>

        </form>

    </div>

</main>

<?php include "footer.php"; ?>

</body>
</html>