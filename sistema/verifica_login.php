<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario_id'])) {
    $_SESSION['mensagem'] = "⚠️ Você precisa estar logado para acessar esta página.";
    header("Location: index.php");
    exit();
}
?>
