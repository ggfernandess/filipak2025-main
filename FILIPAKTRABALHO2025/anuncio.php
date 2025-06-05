<?php
require 'conexao.php';

// Validar se o ID foi passado corretamente
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("
        SELECT 
            p.titulo, 
            p.conteudo, 
            p.imagem, 
            p.modelo_carro, 
            p.ano_carro, 
            p.preco, 
            p.data_publicacao,
            p.tipo_veiculo,
            u.nome AS autor
        FROM publicacoes p
        JOIN usuarios u ON p.usuario_id = u.id
        WHERE p.id = ? AND p.aprovado = 1
    ");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    
    if ($resultado->num_rows > 0) {
        $anuncio = $resultado->fetch_assoc();
    } else {
        die("Anúncio não encontrado.");
    }
} else {
    die("ID inválido.");
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="bootstrap.min.css" rel="stylesheet" type="text/css">
  <title>Detalhes do Anúncio</title>
</head>
<body>

  <header class="bg-dark">
    <div class="container py-1">
      <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
        <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
          <li><img src="icone.png" alt="Página Inicial" style="height: 30px; width: 150px;" class="mt-2 me-4"></li> 
          <li class="d-none d-lg-block"><a href="index.php" class="btn btn-warning mb-3 mb-lg-0"><img src="home.png" style="height: 20px; width: auto;"></a></li>
        </ul>
        <form method="GET" action="buscar.php" class="col-12 col-xl-auto mb-3 mb-xl-0 me-xl-3 d-none d-xl-block">
          <input type="search" name="q" class="form-control form-control-dark" placeholder="Pesquisar..." aria-label="Search" />
        </form>
        <div class="d-block d-xl-none me-3">
          <a href="buscar.php">
          <button class="btn btn-outline-light mb-3 mb-lg-0" type="button" >
            <i>🔍</i>
          </button>
          </a>
        </div>
        <div class="text-end">
          <a href="login.php"><button type="button" class="btn btn-outline-light me-2 mb-3 mb-lg-0">Entrar</button></a>
          <a href="cadastro.php"><button type="button" class="btn btn-warning mb-3 mb-lg-0">Cadastrar</button></a>
        </div>
      </div>
    </div>
  </header>


<div class="container py-2">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card shadow-lg mb-5">
        <?php if (!empty($anuncio['imagem'])): ?>
          <img src="uploads/<?= htmlspecialchars($anuncio['imagem']) ?>" class="card-img-top" alt="Imagem do veículo">
        <?php endif; ?>
        <div class="card-body">
          <h3 class="card-title mb-3"><?= htmlspecialchars($anuncio['titulo']) ?></h3>
          <h5 class="card-subtitle mb-3 text-muted">Publicado por <?= htmlspecialchars($anuncio['autor']) ?> em <?= date("d/m/Y", strtotime($anuncio['data_publicacao'])) ?></h5>
          
          <ul class="list-group list-group-flush mb-3">
            <li class="list-group-item"><strong>Modelo:</strong> <?= htmlspecialchars($anuncio['modelo_carro']) ?></li>
            <li class="list-group-item"><strong>Ano:</strong> <?= htmlspecialchars($anuncio['ano_carro']) ?></li>
            <li class="list-group-item"><strong>Tipo:</strong> <?= ($anuncio['tipo_veiculo'] == 1) ? "Carro" : "Moto" ?></li>
            <li class="list-group-item"><strong>Preço:</strong> R$ <?= number_format($anuncio['preco'], 2, ',', '.') ?></li>
          </ul>
          
          <div class="mt-4">
            <h5>Descrição</h5>
            <p class="card-text"><?= nl2br(htmlspecialchars($anuncio['conteudo'])) ?></p>
          </div>

<div class="text-center mt-4">
  <button onclick="window.print()" class="btn btn-warning">Imprimir</button>
</div>
        </div>
      </div>
    </div>
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
