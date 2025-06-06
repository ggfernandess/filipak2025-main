<?php
session_start();
require_once "conexao.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    header('Content-Type: application/json'); // Muito importante: responde JSON

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

            echo json_encode([
                "success" => true,
                "tipo" => $usuario['tipo']
            ]);
            exit;
        } else {
            echo json_encode([
                "success" => false,
                "error" => "Senha incorreta."
            ]);
            exit;
        }
    } else {
        echo json_encode([
            "success" => false,
            "error" => "Usuário não encontrado."
        ]);
        exit;
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

<header class="bg-dark">
    <div class="container py-1">
        <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
            <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                <li><a href="index.php"><img src="icone.png" alt="Página Inicial" style="height: 30px; width: 150px;" class="mt-2 me-4"></a></li> 
                <li class="d-none d-lg-block"><a href="index.php" class="btn btn-warning mb-3 mb-lg-0"><img src="home.png" style="height: 20px; width: auto;"></a></li>
            </ul>
        </div>
    </div>
</header>

<br>

<div id="mensagem" class="container my-1 py-2 col-12 col-sm-10 col-md-6 col-lg-4 mx-auto"></div>

<div class="container my-1 py-4 bg-light rounded align-items-center col-12 col-sm-10 col-md-6 col-lg-4 mx-auto">
    <form id="formLogin" class="form-signin">
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
            <li class="nav-item"><a href="https://www.reclameaqui.com.br" class="nav-link px-2 text-body-secondary">SAC</a></li>
            <li class="nav-item"><a href="https://veiculos.fipe.org.br/" class="nav-link px-2 text-body-secondary">Tabela Fipe</a></li>
            <li class="nav-item"><a href="sobre.php" class="nav-link px-2 text-body-secondary">Sobre nós</a></li>
        </ul>
        <p class="text-center text-body-secondary">© 2025 Company, Inc</p>
    </footer>
</div>

<!-- Aqui começa o script para enviar o login sem refresh -->
<script>
document.getElementById('formLogin').addEventListener('submit', function(e) {
    e.preventDefault(); // Impede o envio tradicional

    const formData = new FormData(this);

    fetch('login.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        const mensagemDiv = document.getElementById('mensagem');
        if (data.success) {
            mensagemDiv.innerHTML = "<div class='alert alert-success'>Login realizado com sucesso! Redirecionando...</div>";
            setTimeout(() => {
                if (data.tipo === 'admin') {
                    window.location.href = 'admin.php';
                } else {
                    window.location.href = 'user.php';
                }
            }, 400);
        } else {
            mensagemDiv.innerHTML = "<div class='alert alert-danger'>" + data.error + "</div>";
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        document.getElementById('mensagem').innerHTML = "<div class='alert alert-danger'>Erro na requisição.</div>";
    });
});
</script>

</body>
</html>
</html>
