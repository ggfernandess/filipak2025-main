<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'admin') {
    header("Location: login.php");
    exit();
}

require 'conexao.php';

// Buscar todos os anúncios
$sql = "SELECT p.id, u.nome AS nome_usuario, p.titulo, p.modelo_carro, p.ano_carro, p.preco, p.imagem, p.data_publicacao
        FROM publicacoes p
        JOIN usuarios u ON p.usuario_id = u.id
        ORDER BY p.data_publicacao DESC";

$resultado = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Todos os Anúncios</title>
    <link href="bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
<header class="bg-dark">
    <div class="container py-1">
        <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
            <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                <li><img src="icone.png" alt="Página Inicial" style="height: 30px; width: 150px;" class="mt-2 me-4"></li> 
                <li class="d-none d-lg-block">
                    <a href="index.php" class="btn btn-warning mb-3 mb-lg-0">
                        <img src="home.png" style="height: 20px; width: auto;">
                    </a>
                </li>
            </ul>
            <div class="text-end" id="menuUsuario">
                <a href="logout.php" class="btn btn-danger mb-3 mb-lg-0">Sair</a>
            </div>
        </div>
    </div>
</header>

<div class="container mt-5">
    <h2 class="mb-4 text-center">Todos os Anúncios</h2>
    
    <?php if (mysqli_num_rows($resultado) > 0): ?>
        <div class="row g-4">
            <?php while ($anuncio = mysqli_fetch_assoc($resultado)): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 shadow-sm">
                        <?php if (!empty($anuncio['imagem'])): ?>
                            <img src="uploads/<?= htmlspecialchars($anuncio['imagem']) ?>" class="card-img-top" alt="Imagem do carro">
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($anuncio['titulo']) ?></h5>
                            <p class="card-text mb-1"><strong>Modelo:</strong> <?= htmlspecialchars($anuncio['modelo_carro']) ?></p>
                            <p class="card-text mb-1"><strong>Ano:</strong> <?= htmlspecialchars($anuncio['ano_carro']) ?></p>
                            <p class="card-text mb-1"><strong>Preço:</strong> R$ <?= number_format($anuncio['preco'], 2, ',', '.') ?></p>
                            <p class="card-text"><strong>Usuário:</strong> <?= htmlspecialchars($anuncio['nome_usuario']) ?></p>
                        </div>
                        <div class="card-footer d-flex justify-content-between">
                            <a href="excluirAdmin.php?id=<?= $anuncio['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza que deseja excluir este anúncio?')">Excluir</a>
                            <small class="text-muted">Enviado em <?= date("d/m/Y H:i", strtotime($anuncio['data_publicacao'])) ?></small>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-info text-center">Não há anúncios para exibir.</div>
    <?php endif; ?>

    <div class="mt-4 text-center">
        <a href="admin.php" class="btn btn-secondary">← Voltar para o Painel</a>
    </div>
</div>

<div class="container align-items-center">
    <footer class="py-3 my-4">
        <ul class="nav justify-content-center border-bottom pb-3 mb-3">
            <li class="nav-item"><a href="https://www.reclameaqui.com.br" class="nav-link px-2 text-body-secondary">SAC</a></li>
            <li class="nav-item"><a href="https://veiculos.fipe.org.br/" class="nav-link px-2 text-body-secondary">Tabela Fipe</a></li>
            <li class="nav-item"><a href="sobre.php" class="nav-link px-2 text-body-secondary">Sobre nós</a></li>
        </ul>
        <p class="text-center text-body-secondary">© 2025 Company, Inc</p>
    </footer>
</div>
</body>
</html>
