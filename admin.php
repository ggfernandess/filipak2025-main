<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'admin') {
    header("Location: login.php");
    exit();
}

require 'conexao.php';

if (isset($_GET['aprovar'])) {
    $id = intval($_GET['aprovar']);
    $query = "UPDATE publicacoes SET aprovado = 1 WHERE id = $id";
    mysqli_query($conn, $query);
    $baseUrl = dirname($_SERVER['PHP_SELF']);
    header("Location: $baseUrl/index.php");
    exit();
}

if (isset($_GET['excluir'])) {
    $id = intval($_GET['excluir']);
    $query = "DELETE FROM publicacoes WHERE id = $id";
    mysqli_query($conn, $query);
    echo "<p style='color: red;'>Publicação excluída!</p>";
}

echo "<h2>Bem-vindo Admin, " . $_SESSION['usuario_nome'] . "</h2>";

$sql = "SELECT p.id, u.nome AS nome_usuario, p.titulo, p.conteudo
        FROM publicacoes p
        JOIN usuarios u ON p.usuario_id = u.id
        WHERE p.aprovado = 0
        ORDER BY p.data_publicacao DESC";

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    echo "<h3>Publicações pendentes:</h3>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<div style='border: 1px solid #ccc; padding: 10px; margin: 10px;'>";
        echo "<strong>Usuário:</strong> " . $row['nome_usuario'] . "<br>";
        echo "<strong>Título:</strong> " . $row['titulo'] . "<br>";
        echo "<strong>Conteúdo:</strong><br>" . nl2br($row['conteudo']) . "<br><br>";
        echo "<a href='admin.php?aprovar=" . $row['id'] . "'>✅ Aprovar</a> | ";
        echo "<a href='admin.php?excluir=" . $row['id'] . "' onclick=\"return confirm('Tem certeza que deseja excluir?')\">❌ Excluir</a>";
        echo "</div>";
    }
} else {
    echo "<p>Não há publicações pendentes no momento.</p>";
}
