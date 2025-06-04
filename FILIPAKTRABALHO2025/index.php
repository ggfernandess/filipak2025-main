<?php
require 'conexao.php';

$tiposFiltrados = [];
if (isset($_GET['tipo_veiculo']) && is_array($_GET['tipo_veiculo'])) {
    $tiposFiltrados = array_filter($_GET['tipo_veiculo'], function($v) {
        return in_array(intval($v), [1, 2]);
    });
}

$sql = "SELECT 
            p.titulo, 
            p.conteudo, 
            p.imagem, 
            p.modelo_carro,
            p.ano_carro,
            p.preco,
            p.data_publicacao,
            u.nome AS autor,
            p.tipo_veiculo
        FROM publicacoes p
        JOIN usuarios u ON p.usuario_id = u.id
        WHERE p.aprovado = 1";

if (!empty($tiposFiltrados)) {
    $placeholders = implode(',', array_fill(0, count($tiposFiltrados), '?'));
    $sql .= " AND p.tipo_veiculo IN ($placeholders)";
}

$sql .= " ORDER BY p.data_publicacao DESC";

$stmt = mysqli_prepare($conn, $sql);

if (!empty($tiposFiltrados)) {
    $types = str_repeat('i', count($tiposFiltrados));
    $refs = [];
    foreach ($tiposFiltrados as $key => $value) {
        $refs[$key] = &$tiposFiltrados[$key];
    }
    mysqli_stmt_bind_param($stmt, $types, ...$refs);
}

mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$publicacoes_aprovadas = [];
if ($result && mysqli_num_rows($result) > 0) {
    $publicacoes_aprovadas = mysqli_fetch_all($result, MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="bootstrap.min.css" rel="stylesheet" type="text/css" />
  <title>Página Inicial</title>
</head>
<body>

  <header class="bg-dark">
    <div class="container">
      <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
        <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
          <li><img src="icone.png" alt="Página Inicial" style="height: 30px; width: 150px;" class="mt-2 me-4"></li> 
          <li class="d-none d-lg-block"><a href="#" class="nav-link px-2 text-white">Sobre nós e SAC</a></li>
        </ul>
        <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
          <li><a href="#" class="nav-link px-2 text-white me-4"><strong>Comprar</strong></a></li>
          <li><a href="#" class="nav-link px-2 text-white me-4"><strong>Vender</strong></a></li>
          <li><a href="#" class="nav-link px-2 text-white me-4"><strong>Melhores ofertas</strong></a></li>
        </ul>
        <form method="GET" action="buscar.php" class="col-12 col-xl-auto mb-3 mb-xl-0 me-xl-3 d-none d-xl-block">
          <input type="search" class="form-control form-control-dark" placeholder="Pesquisar..." aria-label="Search" />
        </form>
        <div class="d-block d-xl-none me-3">
          <button class="btn btn-outline-light mb-3 mb-lg-0" type="button">
            <i>🔍</i>
          </button>
        </div>
        <div class="text-end">
          <a href="login.php"><button type="button" class="btn btn-outline-light me-2 mb-3 mb-lg-0">Entrar</button></a>
          <a href="cadastro.php"><button type="button" class="btn btn-warning mb-3 mb-lg-0">Cadastrar</button></a>
        </div>
      </div>
    </div>
  </header>

  <!-- Banner com links de motos e carros -->
  <div class="container my-1">
    <div class="d-flex justify-content-center align-items-center bg-dark rounded overflow-hidden" style="height: 300px;">
      <div class="w-100 h-100 d-flex justify-content-center align-items-end" style="background-image: url('carroFundo.jpg'); background-position: left center;">
        <div class="d-flex gap-3 bg-white bg-opacity-10 p-3 rounded w-100 justify-content-center">
          <a href="#" class="text-warning me-5 h4 text-decoration-none"><img src="moto.png" style="height: 30px; width: auto;" class="me-1" />Motos</a>
          <a href="#" class="text-warning h4 text-decoration-none"><img src="carro.png" style="height: 30px; width: auto;" class="me-1" />Carros</a>
        </div>
      </div>
    </div>
  </div>

  <!-- FILTRO TIPO VEICULO -->
  <div class="container mt-4 mb-3">
    <form method="GET" class="d-flex gap-3 align-items-center">
        <label class="form-check-label">
            <input type="checkbox" name="tipo_veiculo[]" value="1" class="form-check-input"
                <?= (in_array(1, $tiposFiltrados)) ? 'checked' : '' ?>>
            Carro
        </label>
        <label class="form-check-label">
            <input type="checkbox" name="tipo_veiculo[]" value="2" class="form-check-input"
                <?= (in_array(2, $tiposFiltrados)) ? 'checked' : '' ?>>
            Moto
        </label>
        <button type="submit" class="btn btn-primary btn-sm">Filtrar</button>
        <a href="publicacoes.php" class="btn btn-secondary btn-sm">Limpar filtro</a>
    </form>
  </div>

  <div class="container mt-4">
    <div class="row">
        <?php foreach ($publicacoes_aprovadas as $pub): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <?php if (!empty($pub['imagem'])): ?>
                        <img src="uploads/<?= htmlspecialchars($pub['imagem']) ?>" class="card-img-top" alt="Imagem do veículo">
                    <?php endif; ?>
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($pub['titulo']) ?></h5>
                        <h6 class="card-subtitle mb-2 text-muted">por <?= htmlspecialchars($pub['autor']) ?></h6>
                        <p class="mb-1">
                            <strong>Modelo:</strong> <?= htmlspecialchars($pub['modelo_carro']) ?><br>
                            <strong>Ano:</strong> <?= htmlspecialchars($pub['ano_carro']) ?><br>
                            <strong>Tipo:</strong> <?= ($pub['tipo_veiculo'] == 1) ? "Carro" : "Moto" ?>
                        </p>
                        <p><strong>Preço:</strong> R$ <?= number_format($pub['preco'], 2, ',', '.') ?></p>
                        <p class="card-text"><?= nl2br(htmlspecialchars($pub['conteudo'])) ?></p>
                    </div>
                    <div class="card-footer text-muted">
                        Publicado em <?= date("d/m/Y", strtotime($pub['data_publicacao'])) ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        <?php if (empty($publicacoes_aprovadas)): ?>
            <p class="text-center">Nenhuma publicação encontrada com esse filtro.</p>
        <?php endif; ?>
    </div>
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
