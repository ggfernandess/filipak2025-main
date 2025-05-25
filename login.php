
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
    <div class="container">
      <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">

        <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
          <li><img src="icone.png" alt="Página Inicial" style="height: 30px; width: 150px;" class="mt-2 me-4" href="index.html"></li> 
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

          <a href="cadastro.php"><button type="button" class="btn btn-warning mb-3 mb-lg-0">Cadastrar</button></a>
        </div>

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

</body>
</html>
