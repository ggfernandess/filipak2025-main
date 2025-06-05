<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'admin') {
    header("Location: login.php");
    exit();
}

require 'conexao.php';

// Aprovar publicação
if (isset($_GET['aprovar'])) {
    $id = intval($_GET['aprovar']);
    $query = "UPDATE publicacoes SET aprovado = 1 WHERE id = $id";
    mysqli_query($conn, $query);
    header("Location: admin.php");
    exit();
}

// Excluir publicação
if (isset($_GET['excluir'])) {
    $id = intval($_GET['excluir']);
    $query = "DELETE FROM publicacoes WHERE id = $id";
    mysqli_query($conn, $query);
    header("Location: admin.php");
    exit();
}

// Buscar publicações pendentes
$sql = "SELECT p.id, u.nome AS nome_usuario, p.titulo, p.conteudo, p.modelo_carro, p.ano_carro, p.data_publicacao, p.imagem,p.contato
        FROM publicacoes p
        JOIN usuarios u ON p.usuario_id = u.id
        WHERE p.aprovado = 0
        ORDER BY p.data_publicacao DESC";

$resultado = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Administração de Publicações</title>
    <link href="bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

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

<div class="container mt-5">
    <h2 class="mb-4 text-center">Painel de Administração</h2>
    <p class="text-center">Bem-vindo, <strong><?= htmlspecialchars($_SESSION['usuario_nome']) ?></strong>!</p>

    <?php if (mysqli_num_rows($resultado) > 0): ?>
        <h4 class="mt-4 mb-3">Publicações Pendentes:</h4>
        <div class="row">
            <?php while ($row = mysqli_fetch_assoc($resultado)): ?>
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm">
                        <?php if (!empty($row['imagem'])): ?>
                            <img src="uploads/<?= htmlspecialchars($row['imagem']) ?>" class="card-img-top" alt="Imagem do carro">
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($row['titulo']) ?></h5>
                            <h6 class="card-subtitle mb-2 text-muted">Por <?= htmlspecialchars($row['nome_usuario']) ?></h6>
                            <p class="mb-1">
                                <strong>Modelo:</strong> <?= htmlspecialchars($row['modelo_carro']) ?><br>
                                <strong>Ano:</strong> <?= htmlspecialchars($row['ano_carro']) ?><br>
                                <strong>Contato:</strong> <?= htmlspecialchars($row['contato']) ?>
                            </p>
                            <p class="card-text"><?= nl2br(htmlspecialchars($row['conteudo'])) ?></p>
                            <div class="d-flex justify-content-between">
                                <a href="admin.php?aprovar=<?= $row['id'] ?>" class="btn btn-success btn-sm">Aprovar</a>
                                <a href="admin.php?excluir=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir esta publicação?')">Excluir</a>
                            </div>
                        </div>
                        <div class="card-footer text-muted">
                            Enviado em <?= date("d/m/Y H:i", strtotime($row['data_publicacao'])) ?>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-info text-center mt-4">Não há publicações pendentes no momento.</div>
    <?php endif; ?>
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
