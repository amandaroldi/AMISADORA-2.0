<?php
session_start();

include "cons.php";
require_once "DLL.php";

if (!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit;
}

if (isset($_POST["id"])) {
    $id = (int) $_POST["id"];
    $usuario_id = (int) $_SESSION["UsuarioId"];

    $consulta = "DELETE FROM carrinho WHERE Id = $id AND usuario_id = $usuario_id";
    banco($server, $user, $password, $db, $consulta);
}

header("Location: carrinho.php");
exit;
?>
