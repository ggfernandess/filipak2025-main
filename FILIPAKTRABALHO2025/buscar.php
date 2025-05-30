<?php
session_start();
require 'conexao.php';

$busca = isset($_GET['q']) ? trim($_GET['q']) : '';

$query = "SELECT p.titulo, p.conteudo, p.modelo_carro, p.ano_carro, p.data_publicacao, p.imagem, p.preco, u.nome AS autor
          FROM publicacoes p
          JOIN usuarios u ON p.usuario_id = u.id
          WHERE p.aprovado = 1 AND (
              p.titulo LIKE ? OR
              p.conteudo LIKE ? OR
              p.modelo_carro LIKE ? OR
              p.ano_carro LIKE ?
          )
          ORDER BY p.data_publicacao DESC";

$stmt = mysqli_prepare($conn, $query);
$param = "%$busca%";
mysqli_stmt_bind_param($stmt, "ssss", $param, $param, $param, $param);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Busca de Publicações</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

  <header class="bg-dark">
    <div class="container">
      <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
        <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
          <li><img src="icone.png" alt="Página Inicial" style="height: 30px; width: 150px;" class="mt-2 me-4"></li> 
          <li class="d-none d-lg-block"><a href="#" class="nav-link px-2 text-white">Sobre nós e SAC</a></li>
        </ul>
        <div class="text-end">
          <a href="login.php"><button type="button" class="btn btn-outline-light me-2 mb-3 mb-lg-0">Entrar</button></a>
          <a href="cadastro.php"><button type="button" class="btn btn-warning mb-3 mb-lg-0">Cadastrar</button></a>
        </div>
      </div>
    </div>
  </header>

<div class="container mt-5">
    <h2 class="text-center mb-4">Resultados da Busca</h2>

    <form method="GET" class="mb-4">
        <div class="input-group">
            <input type="text" name="q" class="form-control" placeholder="Buscar por título, conteúdo, modelo ou ano..." value="<?= htmlspecialchars($busca) ?>">
            <button class="btn btn-warning" type="submit">🔍 Buscar</button>
        </div>
    </form>

    <?php if (mysqli_num_rows($result) > 0): ?>
        <div class="row">
            <?php while($pub = mysqli_fetch_assoc($result)): ?>
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm">
                        <?php if (!empty($pub['imagem'])): ?>
                            <img src="uploads/<?= htmlspecialchars($pub['imagem']) ?>" class="card-img-top" alt="Imagem do carro">
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($pub['titulo']) ?></h5>
                            <h6 class="card-subtitle mb-2 text-muted">Por <?= htmlspecialchars($pub['autor']) ?></h6>
                            <p><strong>Modelo:</strong> <?= htmlspecialchars($pub['modelo_carro']) ?></p>
                            <p><strong>Ano:</strong> <?= htmlspecialchars($pub['ano_carro']) ?></p>
                            <p><strong>Preço:</strong> R$ <?= number_format($pub['preco'], 2, ',', '.') ?></p>
                            <p class="card-text"><?= nl2br(htmlspecialchars($pub['conteudo'])) ?></p>
                        </div>
                        <div class="card-footer text-muted">
                            Anunciado em <?= date("d/m/Y H:i", strtotime($pub['data_publicacao'])) ?>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-warning text-center">Nenhuma publicação encontrada para "<strong><?= htmlspecialchars($busca) ?></strong>".</div>
    <?php endif; ?>
</div>

  <div class="container align-items-center">
    <footer class="py-3 my-4">
      <ul class="nav justify-content-center border-bottom pb-3 mb-3">
        <li class="nav-item"><a href="#" class="nav-link px-2 text-body-secondary">SAC</a></li>
        <li class="nav-item"><a href="https://veiculos.fipe.org.br/" class="nav-link px-2 text-body-secondary">Tabela Fipe</a></li>
        <li class="nav-item"><a href="#" class="nav-link px-2 text-body-secondary">FAQs</a></li>
        <li class="nav-item"><a href="#" class="nav-link px-2 text-body-secondary">Sobre nós</a></li>
      </ul>
      <p class="text-center text-body-secondary">© 2025 Company, Inc</p>
    </footer>
  </div>

</body>
</html>
