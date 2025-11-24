<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

// ==== 1) Confere usuário logado ====
$userId = $_SESSION['user_id'] ?? null;
if (!$userId) {
    echo json_encode(['horarios' => []]);
    exit;
}

// ==== 2) Pega a data: ?data=YYYY-MM-DD ====
$dataIso = $_GET['data'] ?? null;
if (!$dataIso) {
    echo json_encode(['horarios' => []]);
    exit;
}

$dt = DateTime::createFromFormat('Y-m-d', $dataIso);
if (!$dt) {
    echo json_encode(['horarios' => []]);
    exit;
}

// 1 = segunda ... 7 = domingo
$diaSemanaNumero = (int)$dt->format('N');

// Mapa coluna HTML: 0=horário, 1=segunda, 2=terça, etc.
$mapaColunas = [
    1 => 1, // segunda
    2 => 2, // terça
    3 => 3, // quarta
    4 => 4, // quinta
    5 => 5, // sexta
    6 => null,
    7 => null
];

$colunaDia = $mapaColunas[$diaSemanaNumero] ?? null;
if ($colunaDia === null) {
    echo json_encode(['horarios' => []]);
    exit;
}

// ==== 3) JSON de horário ====
$baseJsonDir  = __DIR__ . '/../json/usuarios';
$pastaUsuario = $baseJsonDir . '/' . $userId;
$arquivoHorario = $pastaUsuario . '/horario.json';

if (!file_exists($arquivoHorario)) {
    echo json_encode(['horarios' => []]);
    exit;
}

$dadosHorario = json_decode(file_get_contents($arquivoHorario), true);
if (!is_array($dadosHorario) || !isset($dadosHorario['html'])) {
    echo json_encode(['horarios' => []]);
    exit;
}

$htmlTabela = $dadosHorario['html'];

// ==== 4) Parsear HTML dos TR/TD ====
$dom = new DOMDocument('1.0', 'UTF-8');

libxml_use_internal_errors(true);
$dom->loadHTML('<table><tbody>' . $htmlTabela . '</tbody></table>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
libxml_clear_errors();

$xpath = new DOMXPath($dom);
$linhas = $xpath->query('//tr');

$materias = [];

foreach ($linhas as $tr) {
    $tds = $tr->getElementsByTagName('td');

    if ($tds->length <= $colunaDia) continue;

    $textoMateria = trim($tds->item($colunaDia)->textContent ?? '');

    if ($textoMateria === '') continue;

    if (!in_array($textoMateria, $materias, true)) {
        $materias[] = $textoMateria;
    }
}

// ==== 5) Retorno em ARRAY (do jeito certo) ====
echo json_encode([
    'horarios' => $materias
], JSON_UNESCAPED_UNICODE);
