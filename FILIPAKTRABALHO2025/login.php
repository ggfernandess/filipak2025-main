
<?php
session_start();
require_once "conexao.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $senha = $_POST["senha"];

    $sql = "SELECT id, nome, senha, tipo FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows == 1) {
        $usuario = $resultado->fetch_assoc();
        if (password_verify($senha, $usuario['senha'])) {
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            $_SESSION['usuario_tipo'] = $usuario['tipo'];

            if ($usuario['tipo'] === 'admin') {
                header("Location: admin.php");
            } else {
                header("Location: user.php");
            }
            exit;
        } else {
            $erro = "Senha incorreta.";
        }
    } else {
        $erro = "Usuário não encontrado.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <title>Entrar</title>
    <link href="bootstrap.min.css" rel="stylesheet" type="text/css">
</head>
<body class="text-center">
<header class="bg-dark"> <!-- cabeçalho -->
    <div class="container py-1">
      <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">

        <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
          <li><a href="index.php"><img  src="icone.png" alt="Página Inicial" style="height: 30px; width: 150px;" class="mt-2 me-4"><a></li> 
          <li class="d-none d-lg-block"><a href="index.php" class="btn btn-warning mb-3 mb-lg-0"><img src="home.png" style="height: 20px; width: auto;"></a></li>
        </ul>

      </div>
    </div>
</header>
<br>

    <?php
    if (!empty($_SESSION['mensagem'])) {
        echo "<p style='color:green;'>" . $_SESSION['mensagem'] . "</p>";
        unset($_SESSION['mensagem']);
    }
    if (!empty($erro)) echo "<p style='color:red;'>$erro</p>";
    ?> 

<div class="container my-1 py-4 bg-light rounded align-items-center col-12 col-sm-10 col-md-6 col-lg-4 mx-auto">
    <form action="login.php" method="post" class="form-signin">
        <h1 class="h3 mb-3 font-weight-normal">Entrar:</h1>
        <label for="inputEmail" class="sr-only">Email:</label><br>
        <input type="email" id="inputEmail" class="form-control" placeholder="Email" required autofocus name="email">
        <label for="inputPassword" class="sr-only">Senha:</label><br>
        <input type="password" id="inputPassword" class="form-control" placeholder="Senha" required name="senha"><br><br>

        <button class="btn btn-lg btn-warning btn-block" type="submit">Entrar</button>
        <hr>
        <p>Ainda não tem uma conta? <a href="cadastro.php" class="text-warning">Cadastre-se aqui</a></p>
    </form>
</div>

  <div class="container align-items-center">
    <footer class="py-3 my-4">
      <ul class="nav justify-content-center border-bottom pb-3 mb-3">
        <li class="nav-item"><a href="#" class="nav-link px-2 text-body-secondary">SAC</a></li>
        <li class="nav-item"><a href="#" class="nav-link px-2 text-body-secondary">Tabela Fipe</a></li>
        <li class="nav-item"><a href="#" class="nav-link px-2 text-body-secondary">FAQs</a></li>
        <li class="nav-item"><a href="sobre.php" class="nav-link px-2 text-body-secondary">Sobre nós</a></li>
      </ul>
      <p class="text-center text-body-secondary">© 2025 Company, Inc</p>
    </footer>
  </div>

</body>
</html>
