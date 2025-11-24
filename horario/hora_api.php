<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

// ==== 1) Confere usuário logado (ajusta se seu sistema for outro) ====
$userId = $_SESSION['user_id'] ?? null;
if (!$userId) {
    echo json_encode(['horarios' => []]);
    exit;
}

// ==== 2) Pega a data que veio na URL: ?data=YYYY-MM-DD ====
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

// Mapa número -> índice da coluna na tabela HTML
// 0 = horário, 1 = segunda, 2 = terça, 3 = quarta, 4 = quinta, 5 = sexta
$mapaColunas = [
    1 => 1, // segunda
    2 => 2, // terça
    3 => 3, // quarta
    4 => 4, // quinta
    5 => 5, // sexta
    6 => null, // sábado (se quiser depois, dá pra mapear)
    7 => null  // domingo
];

$colunaDia = $mapaColunas[$diaSemanaNumero] ?? null;
if ($colunaDia === null) {
    // Por enquanto, sábado/domingo sem horário
    echo json_encode(['horarios' => []]);
    exit;
}

// ==== 3) Carrega o JSON de horário do usuário ====
// AJUSTA o caminho se no teu projeto for diferente
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

// ==== 4) Parsear o HTML dos <tr> e <td> ====
$dom = new DOMDocument('1.0', 'UTF-8');

// Silenciar warnings de HTML mal formatado
libxml_use_internal_errors(true);
$dom->loadHTML('<table><tbody>' . $htmlTabela . '</tbody></table>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
libxml_clear_errors();

$xpath = new DOMXPath($dom);
$linhas = $xpath->query('//tr');

$listaHorarios = [];

foreach ($linhas as $tr) {
    $tds = $tr->getElementsByTagName('td');

    // precisa ter pelo menos a coluna do horário e a coluna do dia
    if ($tds->length <= $colunaDia) {
        continue;
    }

    $textoHora = trim($tds->item(0)->textContent ?? '');
    $textoMateria = trim($tds->item($colunaDia)->textContent ?? '');

    // ignora linhas vazias
    if ($textoHora === '' || $textoMateria === '') {
        continue;
    }

    // Monta o texto final, ex: "7h-7h50 — Matemática"
    $listaHorarios[] = $textoHora . ' — ' . $textoMateria;
}

// ==== 5) Retorno em JSON pro calendário ====
echo json_encode([
    'horarios' => $listaHorarios
]);
