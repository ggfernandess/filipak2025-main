<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'usuario') {
    header("Location: login.php");
    exit();
}

require 'conexao.php'; 

echo "Bem-vindo usuário, " . $_SESSION['usuario_nome'] . "<br>";


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'];
    $conteudo = $_POST['conteudo'];
    $usuario_id = $_SESSION['usuario_id'];

    $stmt = mysqli_prepare($conn, "INSERT INTO publicacoes (usuario_id, titulo, conteudo, aprovado) VALUES (?, ?, ?, 0)");

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "iss", $usuario_id, $titulo, $conteudo);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        echo "<p style='color: green;'>Publicação enviada para aprovação!</p>";
    } else {
        echo "Erro ao preparar a consulta: " . mysqli_error($conn);
    }
}
?>


<h3>Nova Publicação</h3>
<form method="POST">
    <input type="text" name="titulo" placeholder="Título" required><br><br>
    <textarea name="conteudo" placeholder="Escreva sua publicação aqui..." required></textarea><br><br>
    <button type="submit">Publicar</button>
</form>
