<?php
session_start();

// precisa estar logado
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "erro", "msg" => "Usuário não autenticado"]);
    exit;
}

$userId = $_SESSION['user_id'];

// pasta do usuário
$baseDir = __DIR__ . '/../json/usuarios/' . $userId;
if (!is_dir($baseDir)) {
    mkdir($baseDir, 0755, true);
}

$arquivo = $baseDir . '/calendario.json';

// lê o JSON enviado
$body = file_get_contents('php://input');
$data = json_decode($body, true);

if (!is_array($data)) {
    echo json_encode(["status" => "erro", "msg" => "Dados inválidos"]);
    exit;
}

// garante estrutura básica
if (!isset($data['cores']) || !is_array($data['cores'])) {
    $data['cores'] = [];
}
if (!isset($data['metas']) || !is_array($data['metas'])) {
    $data['metas'] = [];
}

// salva bonitinho
file_put_contents(
    $arquivo,
    json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
);

echo json_encode([
    "status"  => "ok",
    "arquivo" => $arquivo
]);
