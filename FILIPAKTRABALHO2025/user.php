
<link href="bootstrap.min.css" rel="stylesheet">

  <header class="bg-dark">
    <div class="container">
      <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
        <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
          <li><img src="icone.png" alt="Página Inicial" style="height: 30px; width: 150px;" class="mt-2 me-4"></li> 
          <li class="d-none d-lg-block"><a href="#" class="nav-link px-2 text-white">Sobre nós e SAC</a></li>
        </ul>
      </div>
    </div>
  </header>

<?php
session_start();
require 'conexao.php';

// Verifica se o usuário está logado corretamente
if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['usuario_nome'])) {
    header('Location: login.php');
    exit();
}

$nome_usuario = htmlspecialchars($_SESSION['usuario_nome']);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <title>Área do Usuário</title>
  <link href="bootstrap.min.css" rel="stylesheet" />
</head>
<body class="bg-light">

  <div class="container mt-5">
    <div class="p-5 mb-4 bg-white rounded shadow-sm">
      <h1 class="display-5">Bem-vindo, <?= $nome_usuario ?> 👋</h1>
      <p class="lead">Suas opções como usuário:</p>
      <hr class="my-4" />
      <div class="d-flex gap-3">
        <a href="meusAnuncios.php" class="btn btn-warning btn-lg">Ver Meus Anúncios</a>
        <a href="anuncioCriar.php" class="btn btn-warning btn-lg">Criar Novo Anúncio</a>
      </div>
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