
<?php
session_start();
require_once "conexao.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = trim($_POST["nome"]);
    $email = trim($_POST["email"]);
    $cpf = trim($_POST["cpf"]);
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
        $sql = "INSERT INTO usuarios (nome, email,cpf, senha) VALUES (?,?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $nome, $email,$cpf, $senha_hash);
        if ($stmt->execute()) {
            $_SESSION['mensagem'] = "Cadastro realizado com sucesso! Você pode entrar agora.";
            header("Location: login.php");
            exit;
        } else {
            $erro = "Erro ao cadastrar, tente novamente.";
        }
    }
    echo "Digitada: $senha<br>";
    echo "Hash no banco: " . $usuario['senha'];
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
          <li><a href="index.php"><img  src="icone.png" alt="Página Inicial" style="height: 30px; width: 150px;" class="mt-2 me-4"><a></li> 
          <li class="d-none d-lg-block"><a href="#" class="nav-link px-2 text-white">Sobre nós e SAC</a></li>
        </ul>

      </div>
    </div>
</header>
<br>
    <?php if (!empty($erro)) echo "<p style='color:red;'>$erro</p>"; ?>
<div class="container my-1 py-4 bg-light rounded align-items-center col-12 col-sm-10 col-md-6 col-lg-4 mx-auto">
    <form action="" method="post" class="form-signin">
        <h1 class="h3 mb-3 font-weight-normal">Cadastrar-se:</h1>
        <label for="inputEmail" class="sr-only">Nome:</label><br>
        <input type="text" class="form-control" placeholder="Nome" required autofocus name="nome">
        <label for="inputEmail" class="sr-only">Email:</label><br>
        <input type="email" class="form-control" placeholder="Email" required autofocus name="email">
        <label for="inputCpf" class="sr-only">CPF:</label><br>
        <input type="cpf" class="form-control" placeholder="CPF" required autofocus name="cpf">
        <label for="inputPassword" class="sr-only">Senha:</label><br>
        <input type="password" class="form-control" placeholder="Senha" required name="senha"><br><br>

        <button class="btn btn-lg btn-warning btn-block" type="submit">Cadastrar</button>
        <hr>
        <p>Já tem conta? <a href="login.php" class="text-warning">Entrar aqui</a></p>
    </form>
</div>


  <div class="container align-items-center">
    <footer class="py-3 my-4">
      <ul class="nav justify-content-center border-bottom pb-3 mb-3">
        <li class="nav-item"><a href="#" class="nav-link px-2 text-body-secondary">SAC</a></li>
        <li class="nav-item"><a href="#" class="nav-link px-2 text-body-secondary">Tabela Fipe</a></li>
        <li class="nav-item"><a href="#" class="nav-link px-2 text-body-secondary">FAQs</a></li>
        <li class="nav-item"><a href="#" class="nav-link px-2 text-body-secondary">Sobre nós</a></li>
      </ul>
      <p class="text-center text-body-secondary">© 2025 Company, Inc</p>
    </footer>
  </div>


</body>
</html>

