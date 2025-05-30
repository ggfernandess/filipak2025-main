<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'usuario') {
    header("Location: login.php");
    exit();
}

require 'conexao.php'; 

$usuario_id = $_SESSION['usuario_id'];

$sql = "SELECT id, titulo, modelo_carro, ano_carro, preco, imagem FROM publicacoes WHERE usuario_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$resultado = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Meus Anúncios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <h1 class="mb-4 text-center">Meus Anúncios</h1>

        <?php if ($resultado->num_rows > 0): ?>
            <div class="row g-4">
                <?php while ($anuncio = $resultado->fetch_assoc()): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 shadow-sm">
                            <img src="uploads/<?= htmlspecialchars($anuncio['imagem']) ?>" class="card-img-top" alt="Imagem do carro">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($anuncio['titulo']) ?></h5>
                                <p class="card-text mb-1"><strong>Modelo:</strong> <?= htmlspecialchars($anuncio['modelo_carro']) ?></p>
                                <p class="card-text mb-1"><strong>Ano:</strong> <?= htmlspecialchars($anuncio['ano_carro']) ?></p>
                                <p class="card-text"><strong>Preço:</strong> R$ <?= number_format($anuncio['preco'], 2, ',', '.') ?></p>
                            </div>
                            <div class="card-footer d-flex justify-content-between">
                                <a href="excluirAnuncio.php?id=<?= $anuncio['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza que deseja excluir este anúncio?')">Excluir</a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-info text-center">Você ainda não criou nenhum anúncio.</div>
        <?php endif; ?>

        <div class="mt-4 text-center">
            <a href="user.php" class="btn btn-secondary">← Voltar para o Painel</a>
        </div>
    </div>
</body>
</html>
