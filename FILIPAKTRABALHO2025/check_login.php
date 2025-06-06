<?php
session_start();

$response = [
    'logged_in' => false,
    'tipo' => null,
    'nome' => null
];

if (isset($_SESSION['usuario_id'])) {
    $response['logged_in'] = true;
    $response['tipo'] = $_SESSION['usuario_tipo']; // admin ou usuario
    $response['nome'] = $_SESSION['usuario_nome']; // nome do usuario
}

header('Content-Type: application/json');
echo json_encode($response);
?>
