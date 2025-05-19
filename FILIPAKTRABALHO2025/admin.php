<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'admin') {
    header("Location: login.php");
    exit();
}

echo "Bem-vindo Admin, " . $_SESSION['usuario_nome'] . "<br>"; 
echo "AQUI DEVE APARECER AS PUBLICAÇÕES DOS USUARIOS E O ADM DEVE APROVAR"
?>
