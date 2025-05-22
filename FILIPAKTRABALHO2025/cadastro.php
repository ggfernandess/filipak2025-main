
<?php
session_start();
require_once "conexao.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = trim($_POST["nome"]);
    $email = trim($_POST["email"]);
    $senha = $_POST["senha"];

   
    $sql = "SELECT id FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();  

    if ($stmt->num_rows > 0) {
        $erro = "Email já cadastrado.";
    } else {
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
        $sql = "INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $nome, $email, $senha_hash);
        if ($stmt->execute()) {
            $_SESSION['mensagem'] = "Cadastro realizado com sucesso! Você pode entrar agora.";
            header("Location: login.php");
            exit;
        } else {
            $erro = "Erro ao cadastrar, tente novamente.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <title>Cadastrar</title>
    <link href="bootstrap.min.css" rel="stylesheet" type="text/css">
</head>
<body class="text-center">
<header class="bg-dark"> <!-- cabeçalho -->
    <div class="container">
      <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">

        <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
          <li><img src="icone.png" alt="Página Inicial" style="height: 30px; width: 150px;" class="mt-2 me-4"></li> 
          <li class="d-none d-lg-block"><a href="#" class="nav-link px-2 text-white">Sobre nós e SAC</a></li>
        </ul>

        <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0"> <!-- opções -->
          <li><a href="#" class="nav-link px-2 text-white me-4"><strong>Comprar</strong></a></li>
          <li><a href="#" class="nav-link px-2 text-white me-4"><strong>Vender</strong></a></li>
          <li><a href="#" class="nav-link px-2 text-white me-4"><strong>Melhores ofertas</strong></a></li>
        </ul>

        <form class="col-12 col-xl-auto mb-3 mb-xl-0 me-xl-3 d-none d-xl-block"> <!-- barra de pesquisa -->
          <input type="search" class="form-control form-control-dark" placeholder="Pesquisar..." aria-label="Search">
        </form>

        <div class="d-block d-xl-none me-3">
          <button class="btn btn-outline-light mb-3 mb-lg-0" type="button">
            <i>🔍</i>
          </button>
        </div>

        <div class="text-end"> <!-- botões -->

          <a href="login.php"><button type="button" class="btn btn-warning mb-3 mb-lg-0">Entrar</button></a>
        </div>

      </div>
    </div>
</header>
<br>
    <?php if (!empty($erro)) echo "<p style='color:red;'>$erro</p>"; ?>
<div class="container my-1 py-4 bg-light rounded align-items-center col-12 col-sm-10 col-md-6 col-lg-4 mx-auto">
    <form action="login.php" method="post" class="form-signin">
        <h1 class="h3 mb-3 font-weight-normal">Cadastrar-se:</h1>
        <label for="inputEmail" class="sr-only">Nome:</label><br>
        <input type="text" class="form-control" placeholder="Nome" required autofocus name="nome">
        <label for="inputEmail" class="sr-only">Email:</label><br>
        <input type="email" class="form-control" placeholder="Email" required autofocus name="email">
        <label for="inputPassword" class="sr-only">Senha:</label><br>
        <input type="password" class="form-control" placeholder="Senha" required name="senha"><br><br>

        <button class="btn btn-lg btn-warning btn-block" type="submit">Entrar</button>
        <hr>
        <p>Já tem conta? <a href="login.php" class="text-warning">Entrar aqui</a></p>
    </form>
</div>
</body>
</html>

