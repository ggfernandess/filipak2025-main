<?php
session_start();

// Destroi todas as variáveis de sessão
$_SESSION = array();

// Se quiser forçar apagar o cookie de sessão também (boa prática):
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Finalmente, destrói a sessão
session_destroy();

// Redireciona para a página inicial
header("Location: index.php");
exit;
?>