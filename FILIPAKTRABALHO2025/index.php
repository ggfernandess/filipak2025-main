<?php
session_start();
require 'conexao.php';

$tiposFiltrados = [];
if (isset($_GET['tipo_veiculo']) && is_array($_GET['tipo_veiculo'])) {
    $tiposFiltrados = array_filter($_GET['tipo_veiculo'], function($v) {
        return in_array(intval($v), [1, 2]);
    });
}

$sql = "SELECT 
            p.id,
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
    <div class="container py-1">
      <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
        <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
          <li><img src="icone.png" alt="Página Inicial" style="height: 30px; width: 150px;" class="mt-2 me-4"></li> 
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
          <div class="text-end" id="botoesAuth">
            <a href="login.php" class="btn btn-outline-light me-2 mb-3 mb-lg-0" id="botaoEntrar">Entrar</a>
            <a href="cadastro.php" class="btn btn-warning mb-3 mb-lg-0" id="botaoCadastrar">Cadastrar</a>
          </div>

          <div class="text-end d-none" id="menuUsuario">
            <a href="#" id="linkPerfil" class="btn btn-outline-light me-2 mb-3 mb-lg-0">Meu Perfil</a>
            <a href="logout.php" class="btn btn-danger mb-3 mb-lg-0">Sair</a>
          </div>
      </div>
    </div>
  </header>

<div class="container my-1 py-1">
  <div class="d-flex justify-content-center align-items-center bg-dark rounded overflow-hidden" style="height: 300px;">
    <div class="w-100 h-100 d-flex justify-content-center align-items-end" style="background-image: url('carroFundo.png'); background-position: left center;">
      <div class="d-flex gap-3 bg-white bg-opacity-10 p-3 rounded w-100 justify-content-center">
        <a href="?tipo_veiculo[]=2" class="btn btn-warning me-5 mb-3 mb-lg-0 <?php if (in_array(2, $tiposFiltrados)) echo 'fw-bold text-decoration-underline'; ?>">
          <img src="moto.png" style="height: 30px; width: auto;" class="me-1">Motos
        </a>
        <a href="?tipo_veiculo[]=1" class="btn btn-warning me-5 mb-3 mb-lg-0 <?php if (in_array(1, $tiposFiltrados)) echo 'fw-bold text-decoration-underline'; ?>">
          <img src="carro.png" style="height: 30px; width: auto;" class="me-1">Carros
        </a>
                <a href="?" class="btn btn-outline-light me-2 mb-3 mb-lg-0">
                  <img src="escova-de-mao.png" style="height: 30px; width: auto;" class="me-1">Limpar filtro
                </a>
      </div>
    </div>
  </div>
</div>


<div class="container mt-4">
  <div class="card shadow p-4">
  <h1 class="text-center">Anúncios</h1>

  <br>
  <br>
  
    <div class="row">
        <?php foreach ($publicacoes_aprovadas as $pub): ?>
            <div class="col-md-4 mb-4">
              <a href="anuncio.php?id=<?= urlencode($pub['id']) ?>" class="text-decoration-none text-dark">
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
              </a>
            </div>
        <?php endforeach; ?>
        <?php if (empty($publicacoes_aprovadas)): ?>
            <p class="text-center">Nenhuma publicação encontrada.</p>
        <?php endif; ?>
    </div>
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


  <script>
document.addEventListener("DOMContentLoaded", function() {
    fetch('check_login.php')
        .then(response => response.json())
        .then(data => {
            if (data.logged_in) {
                const botoesAuth = document.getElementById('botoesAuth');
                if (botoesAuth) {
                    botoesAuth.classList.add('d-none');
                }

                const menuUser = document.getElementById('menuUsuario');
                if (menuUser) {
                    menuUser.classList.remove('d-none');
                }
                const perfilLink = document.getElementById('linkPerfil');
                if (perfilLink) {
                    if (data.tipo === 'admin') {
                        perfilLink.setAttribute('href', 'admin.php');
                    } else {
                        perfilLink.setAttribute('href', 'user.php');
                    }

                    if (data.nome) {
                        perfilLink.textContent = `${data.nome}`;
                    }
                }
            }
        })
        .catch(error => console.error('Erro ao verificar login:', error));
});
</script>
</body>
</html>
