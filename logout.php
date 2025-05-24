<?php
// Inicia a sessão caso ainda não tenha sido iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
session_destroy();
header("Location: ../index.php");
exit;
?>
