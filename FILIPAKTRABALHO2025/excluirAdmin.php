<?php
session_start();
require 'conexao.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $anuncio_id = intval($_GET['id']);

    // Buscar a imagem
    $sql = "SELECT imagem FROM publicacoes WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $anuncio_id);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $anuncio = $resultado->fetch_assoc();

        // Deletar imagem do servidor
        if (!empty($anuncio['imagem']) && file_exists("uploads/" . $anuncio['imagem'])) {
            unlink("uploads/" . $anuncio['imagem']);
        }

        // Deletar anúncio
        $sql_delete = "DELETE FROM publicacoes WHERE id = ?";
        $stmt_delete = $conn->prepare($sql_delete);
        $stmt_delete->bind_param("i", $anuncio_id);
        $stmt_delete->execute();

        header("Location: todosAnuncios.php");
        exit();
    } else {
        echo "Anúncio não encontrado.";
    }
} else {
    echo "ID do anúncio não especificado.";
}
?>
