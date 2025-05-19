<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'usuario') {
    header("Location: login.php");
    exit();
}

echo "Bem-vindo usuario, " . $_SESSION['usuario_nome'] . "<br>";
echo "TEM QUE COLOCAR PRO USUARIO POSTAR ALGO AQUI"
?>
