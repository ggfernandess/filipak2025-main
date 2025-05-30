<?php
session_start();
require 'conexao.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

// Verifica se o ID do anúncio foi passado via GET
if (isset($_GET['id'])) {
    $anuncio_id = $_GET['id'];
    $usuario_id = $_SESSION['usuario_id'];

    // Verifica se o anúncio pertence ao usuário
    $sql = "SELECT imagem FROM publicacoes WHERE id = ? AND usuario_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $anuncio_id, $usuario_id);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $anuncio = $resultado->fetch_assoc();

        // Exclui o arquivo da imagem, se existir
        if (!empty($anuncio['imagem']) && file_exists("uploads/" . $anuncio['imagem'])) {
            unlink("uploads/" . $anuncio['imagem']);
        }

        // Exclui o anúncio do banco
        $sql_delete = "DELETE FROM publicacoes WHERE id = ? AND usuario_id = ?";
        $stmt_delete = $conn->prepare($sql_delete);
        $stmt_delete->bind_param("ii", $anuncio_id, $usuario_id);
        $stmt_delete->execute();

        header("Location: meusAnuncios.php");
        exit();
    } else {
        echo "Anúncio não encontrado ou você não tem permissão para excluí-lo.";
    }
} else {
    echo "ID do anúncio não especificado.";
}
?>