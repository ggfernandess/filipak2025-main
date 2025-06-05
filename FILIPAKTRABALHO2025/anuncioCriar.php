<link href="bootstrap.min.css" rel="stylesheet">

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

<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'usuario') {
    header("Location: login.php");
    exit();
}

require 'conexao.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'];
    $conteudo = $_POST['conteudo'];
    $modelo_carro = $_POST['modelo_carro'];
    $ano_carro = intval($_POST['ano_carro']);
    $usuario_id = $_SESSION['usuario_id'];
    $preco = isset($_POST['preco']) ? floatval($_POST['preco']) : 0;
    $tipo_veiculo = isset($_POST['tipo_veiculo']) ? intval($_POST['tipo_veiculo']) : 1;

    $imagem_nome = null;
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == 0) {
        $extensao = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
        $imagem_nome = uniqid() . "." . $extensao;
        move_uploaded_file($_FILES['imagem']['tmp_name'], "uploads/" . $imagem_nome);
    }

    $stmt = mysqli_prepare($conn, "INSERT INTO publicacoes 
        (usuario_id, titulo, conteudo, modelo_carro, ano_carro, data_publicacao, preco, imagem, tipo_veiculo, aprovado) 
        VALUES (?, ?, ?, ?, ?, NOW(), ?, ?, ?, 0)");

    if ($stmt) {
       mysqli_stmt_bind_param($stmt, "isssidsi", $usuario_id, $titulo, $conteudo, $modelo_carro, $ano_carro, $preco, $imagem_nome, $tipo_veiculo);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        echo "<p class='alert alert-success'>Publicação enviada para aprovação!</p>";
    } else {
        echo "Erro: " . mysqli_error($conn);
    }
}
?>
<div class="container align-items-center text-center py-3">
  <div class="p-5 mb-4 bg-white rounded shadow-sm">
  <h3>Nova Publicação</h3>
  <form method="POST" enctype="multipart/form-data" class="mb-4 ">
      <input type="text" name="titulo" placeholder="Título" class="form-control mb-2" minlength="2" maxlength="20" required>

      <input type="text" name="modelo_carro" placeholder="Modelo do carro" class="form-control mb-2" minlength="2" maxlength="24" required>

      <input type="number" name="ano_carro" placeholder="Ano do carro" class="form-control mb-2" min="1900" max="<?= date('Y') ?>" required>

      <input type="number" step="0.01" min="0" class="form-control mb-2" id="preco" name="preco" placeholder="Preço" max="1000000" required>

    <div style="margin-left: 1cm;">
    <label class="form-label fw-bold">Tipo de Veículo:</label><br>
    <div class="form-check form-check-inline mb-2">
        <input class="form-check-input" type="radio" name="tipo_veiculo" id="carro" value="1" checked>
        <label class="form-check-label" for="carro">Carro</label>
    </div>
    <div class="form-check form-check-inline mb-2">
        <input class="form-check-input" type="radio" name="tipo_veiculo" id="moto" value="2">
        <label class="form-check-label" for="moto">Moto</label>
    </div>
  </div>
      <textarea name="conteudo" placeholder="Escreva sua descrição..." class="form-control mb-2" minlength="2" maxlength="255" required></textarea>

      <input type="file" name="imagem" accept="image/*" class="form-control mb-3">

      <button type="submit" class="btn btn-warning">Publicar</button>
  </form>
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
