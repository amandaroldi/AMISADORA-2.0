<?php
session_start();

include "cons.php";
require_once "DLL.php";

if (!isset($_SESSION["login"])) {
    $produto_id = $_POST["produto_id"] ?? "";
    $_SESSION["pendente"] = $produto_id;
    header("Location: login.php");
    exit;
}

$produto_id = (int) $_POST["produto_id"];
$usuario_id = (int) $_SESSION["UsuarioId"];

// Verifica se esse produto já está no carrinho do usuário
$consulta = "SELECT * FROM carrinho WHERE usuario_id = $usuario_id AND produto_id = $produto_id";
$resultado = banco($server, $user, $password, $db, $consulta);
$linha = $resultado->fetch_assoc();

if ($linha) {
    // Já está no carrinho: só aumenta a quantidade
    $nova_quantidade = $linha["quantidade"] + 1;
    $consulta = "UPDATE carrinho SET quantidade = $nova_quantidade WHERE Id = " . $linha["Id"];
} else {
    // Ainda não está no carrinho: insere com quantidade 1
    $consulta = "INSERT INTO carrinho (usuario_id, produto_id, quantidade) VALUES ($usuario_id, $produto_id, 1)";
}

banco($server, $user, $password, $db, $consulta);

header("Location: index.php");
exit;
?>
