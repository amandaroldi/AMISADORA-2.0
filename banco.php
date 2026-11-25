<?php
session_start();

include "cons.php";
require_once "DLL.php";

extract($_POST);

// B1: Cadastrar os dados pessoais do usuário (etapa 1 do cadastro)
if (isset($B1)) {

    // Limpa qualquer sessão de login anterior, para não misturar com a conta nova
    unset($_SESSION["login"]);
    unset($_SESSION["UsuarioId"]);

    $nome = addslashes($nome ?? "");
    $cpf = addslashes($cpf ?? "");
    $endereco = addslashes($endereco ?? "");
    $bairro = addslashes($bairro ?? "");
    $cidade = addslashes($cidade ?? "");
    $estado = addslashes($estado ?? "");
    $cep = addslashes($cep ?? "");

    $consulta = "INSERT INTO usuarios (Nome, CPF, Endereco, Bairro, Cidade, Estado, CEP) VALUES ('$nome', '$cpf', '$endereco', '$bairro', '$cidade', '$estado', '$cep')";

    banco($server, $user, $password, $db, $consulta);

    // Busca o id que acabou de ser gerado, para usar na etapa 2
    $consulta = "SELECT Id FROM usuarios WHERE CPF = '$cpf'";
    $resultado = banco($server, $user, $password, $db, $consulta);
    $linha = $resultado->fetch_assoc();

    $_SESSION["UsuarioId"] = $linha["Id"];

    header("Location: cadastro_login.php");
    exit;
}


// B2: Cadastrar o login e a senha, vinculados ao usuário (etapa 2 do cadastro)
if (isset($B2)) {

    if (!isset($_SESSION["UsuarioId"])) {
        header("Location: cadastro.php");
        exit;
    }

    $usuario_id = $_SESSION["UsuarioId"];
    $login_novo = addslashes($login ?? "");
    $senhaCriptografada = password_hash($senha ?? "", PASSWORD_DEFAULT);

    $consulta = "INSERT INTO login (Login, Senha, usuario_id) VALUES ('$login_novo', '$senhaCriptografada', $usuario_id)";

    banco($server, $user, $password, $db, $consulta);

    header("Location: login.php");
    exit;
}


// B3: Fazer login (autenticação cruzando login + usuarios)
if (isset($B3)) {

    $login_tentativa = addslashes($login ?? "");
    $senha_tentativa = $senha ?? "";

    $consulta = "SELECT * FROM login WHERE Login = '$login_tentativa'";
    $resultado = banco($server, $user, $password, $db, $consulta);
    $linha = $resultado->fetch_assoc();

    if ($linha && password_verify($senha_tentativa, $linha["Senha"])) {

        $consulta = "SELECT * FROM usuarios WHERE Id = " . (int) $linha["usuario_id"];
        $resultado = banco($server, $user, $password, $db, $consulta);
        $usuario = $resultado->fetch_assoc();

        $_SESSION["login"] = $linha["Login"];
        $_SESSION["UsuarioId"] = $usuario["Id"];

        // Se o usuário tentou adicionar um produto ao carrinho antes de logar, completa isso agora
        if (!empty($_POST["produto_id"])) {

            $produto_id = (int) $_POST["produto_id"];
            $usuario_id = $usuario["Id"];

            $consulta = "SELECT * FROM carrinho WHERE usuario_id = $usuario_id AND produto_id = $produto_id";
            $resultado = banco($server, $user, $password, $db, $consulta);
            $existente = $resultado->fetch_assoc();

            if ($existente) {
                $nova_quantidade = $existente["quantidade"] + 1;
                $consulta = "UPDATE carrinho SET quantidade = $nova_quantidade WHERE Id = " . $existente["Id"];
            } else {
                $consulta = "INSERT INTO carrinho (usuario_id, produto_id, quantidade) VALUES ($usuario_id, $produto_id, 1)";
            }

            banco($server, $user, $password, $db, $consulta);
        }

        header("Location: index.php");
        exit;

    } else {
        header("Location: erro.php");
        exit;
    }
}

?>
