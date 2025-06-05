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
<link href="bootstrap.min.css" rel="stylesheet">
</head>

    <header class="bg-dark">
    <div class="container py-1">
      <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
        <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
          <li><img src="icone.png" alt="Página Inicial" style="height: 30px; width: 150px;" class="mt-2 me-4"></li> 
          <li class="d-none d-lg-block"><a href="index.php" class="btn btn-warning mb-3 mb-lg-0"><img src="home.png" style="height: 20px; width: auto;"></a></li>
        </ul>
      </div>
    </div>
  </header>

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

      <div class="container align-items-center">
    <footer class="py-3 my-4">
      <ul class="nav justify-content-center border-bottom pb-3 mb-3">
        <li class="nav-item"><a href="#" class="nav-link px-2 text-body-secondary">SAC</a></li>
        <li class="nav-item"><a href="https://veiculos.fipe.org.br/" class="nav-link px-2 text-body-secondary">Tabela Fipe</a></li>
        <li class="nav-item"><a href="#" class="nav-link px-2 text-body-secondary">FAQs</a></li>
        <li class="nav-item"><a href="sobre.php" class="nav-link px-2 text-body-secondary">Sobre nós</a></li>
      </ul>
      <p class="text-center text-body-secondary">© 2025 Company, Inc</p>
    </footer>
  </div>
</body>
</html>
