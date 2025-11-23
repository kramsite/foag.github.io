<?php
session_start();

header('Content-Type: application/json; charset=utf-8');

// Exigir login
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['status' => 'erro', 'msg' => 'Usuário não autenticado']);
    exit;
}

$userId = $_SESSION['user_id'];

// Caminho do JSON por usuário
$baseJsonDir    = __DIR__ . '/../json/usuarios';
$pastaUsuario   = $baseJsonDir . '/' . $userId;
$arquivoHorario = $pastaUsuario . '/horario.json';

if (!is_dir($pastaUsuario)) {
    mkdir($pastaUsuario, 0755, true);
}

// Lê o corpo JSON
$raw = file_get_contents('php://input');
$data = json_decode($raw, true);

if (!is_array($data)) {
    http_response_code(400);
    echo json_encode(['status' => 'erro', 'msg' => 'JSON inválido']);
    exit;
}

$html = isset($data['html']) ? (string)$data['html'] : '';

$salvar = [
    'html' => $html
];

file_put_contents(
    $arquivoHorario,
    json_encode($salvar, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
);

echo json_encode([
    'status' => 'ok',
    'file'   => $arquivoHorario
]);
