<?php
date_default_timezone_set('America/Sao_Paulo');

// ====== LER ÚLTIMO USUÁRIO DO JSON ======
$caminhoJson = __DIR__ . '/../json/usuarios.json';

$usuario = null;
if (file_exists($caminhoJson)) {
    $conteudo = file_get_contents($caminhoJson);
    $lista = json_decode($conteudo, true);

    if (is_array($lista) && count($lista) > 0) {
        $usuario = end($lista); // último cadastro
    }
}

// Nome (ajusta o campo se for diferente)
$nome = $usuario['nome'] ?? 'Aluno FOAG';

// ====== SAUDAÇÃO PELO HORÁRIO ======
$hora = (int) date('H');
if ($hora >= 5 && $hora < 12) {
    $saudacao = 'Bom dia';
} elseif ($hora >= 12 && $hora < 18) {
    $saudacao = 'Boa tarde';
} else {
    $saudacao = 'Boa noite';
}

// ====== FREQUÊNCIA / FALTAS ======
$frequenciaGeral = $usuario['frequencia_geral'] ?? null;
$faltasMes       = $usuario['faltas_mes'] ?? null;

// ====== AGENDA HOJE / PRÓXIMOS DIAS ======
$hoje = date('Y-m-d');
$itensHoje = [];
$proximosEventos = [];

if (!empty($usuario['agenda']) && is_array($usuario['agenda'])) {
    foreach ($usuario['agenda'] as $item) {
        if (empty($item['data'])) continue;

        if ($item['data'] === $hoje) {
            $itensHoje[] = $item;
        } elseif ($item['data'] > $hoje) {
            $proximosEventos[] = $item;
        }
    }

    // Ordena próximos eventos por data
    usort($proximosEventos, function($a, $b) {
        return strcmp($a['data'], $b['data']);
    });
}

// Textos de resumo
if ($frequenciaGeral !== null) {
    $textoFrequencia = "Sua frequência geral está em <strong>{$frequenciaGeral}%</strong>.";
} else {
    $textoFrequencia = "Sua frequência ainda não foi cadastrada no sistema.";
}

if ($faltasMes !== null) {
    $textoFaltas = "Neste mês, você tem <strong>{$faltasMes}</strong> falta(s) registrada(s).";
} else {
    $textoFaltas = "Ainda não há registro de faltas neste mês.";
}

if (count($itensHoje) > 0) {
    $textoAgenda = "Hoje você tem <strong>" . count($itensHoje) . "</strong> compromisso(s) marcado(s).";
} else {
    $textoAgenda = "Hoje não há nada marcado no seu FOAG. Bom momento pra organizar os estudos. 😉";
}

// ====== RESUMO DA SEMANA (horas de estudo) ======
$horasSemana = $usuario['horas_estudo_semana'] ?? 0;
$metaHoras   = $usuario['meta_horas_semana'] ?? 10;
$diasSemana  = $usuario['dias_estudados_semana'] ?? 0;

if ($metaHoras > 0) {
    $percEstudo = min(100, max(0, round(($horasSemana / $metaHoras) * 100)));
} else {
    $percEstudo = 0;
}

// ====== MATÉRIAS EM ALERTA (notas baixas) ======
$notasBaixas = [];
if (!empty($usuario['notas_baixas']) && is_array($usuario['notas_baixas'])) {
    $notasBaixas = $usuario['notas_baixas'];
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Organizador</title>
  <link rel="stylesheet" href="inicio.css" />
  <link rel="stylesheet" href="dark_agenda.css">
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
  <script src="../m.escuro/dark-mode.js"></script>

  <!-- Estilos básicos do modal da FOGi -->
  <style>
    #icon-fogi {
      cursor: pointer;
      transition: 0.2s;
    }
    #icon-fogi:hover {
      color: #38a5ff;
      transform: scale(1.1);
    }

    /* Modal full-screen da FOGi */
    #fogi-modal {
      display: none;
      position: fixed;
      inset: 0;
      z-index: 9999;
      background: rgba(0,0,0,0.5);
      backdrop-filter: blur(4px);
      align-items: center;
      justify-content: center;
    }

    #fogi-modal .fogi-container {
      background: #ffffff;
      width: 90%;
      max-width: 1100px;
      height: 80vh;
      border-radius: 12px;
      overflow: hidden;
      display: flex;
      flex-direction: column;
      box-shadow: 0 10px 35px rgba(0,0,0,0.2);
    }

    #fogi-modal .fogi-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      background: #38a5ff;
      color: #fff;
      padding: 8px 14px;
      font-weight: 600;
      font-size: 0.95rem;
    }

    #fogi-close {
      border: none;
      background: #ffffff;
      color: #333;
      padding: 4px 10px;
      border-radius: 6px;
      cursor: pointer;
      font-size: 0.85rem;
    }

    #fogi-close:hover {
      background: #f1f1f1;
    }

    #fogi-iframe {
      flex: 1;
      border: none;
      width: 100%;
      height: 100%;
    }
  </style>
</head>

<body>
  <header class="cabecalho">
    FOAG
    <div class="header-icons">
      <i id="themeToggle" class="fa-solid fa-moon" title="Modo Escuro"></i>
      <i id="icon-perfil" class="fa-regular fa-user" title="Perfil"></i>
      <i id="icon-fogi" class="fa-solid fa-robot" title="Assistente FOAG — FOGi"></i>
      <i id="icon-sair" class="fa-solid fa-right-from-bracket" title="Sair"></i>
    </div>
  </header>

<main>
    <div class="home-wrapper">

        <!-- CARD PRINCIPAL -->
        <section class="home-card">
            <div class="home-card-inner">
                <div class="pill-dia">
                    <span class="pill-ponto"></span>
                    Hoje é <?= date('d/m') ?> • FOAG ligado
                </div>

                <div class="home-greeting">
                    <?= $saudacao ?>, <span><?= htmlspecialchars($nome) ?></span> 👋
                </div>
                <p class="home-sub">
                    Esse é o seu painel inicial do FOAG. Aqui você vê sua frequência, compromissos do dia
                    e acessa rapidinho o que mais usa.
                </p>

                <div class="home-resumo-grid">
                    <div class="mini-stat">
                        <div class="mini-stat-label">Frequência geral</div>
                        <div class="mini-stat-value">
                            <?= $frequenciaGeral !== null ? $frequenciaGeral . '%' : '--' ?>
                        </div>
                    </div>
                    <div class="mini-stat">
                        <div class="mini-stat-label">Faltas no mês</div>
                        <div class="mini-stat-value">
                            <?= $faltasMes !== null ? $faltasMes : '--' ?>
                        </div>
                    </div>
                    <div class="mini-stat">
                        <div class="mini-stat-label">Compromissos hoje</div>
                        <div class="mini-stat-value">
                            <?= count($itensHoje) ?>
                        </div>
                    </div>
                </div>

                <div class="fogi-tip">
                    <div class="fogi-avatar">😼</div>
                    <div>
                        <strong>FOGi por aqui</strong><br>
                        <span>“Se quiser, eu te ajudo a montar um plano de estudo só pra essa semana.”</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- COLUNA DIREITA -->
        <div class="home-side">

            <!-- SEU DIA HOJE -->
            <section class="home-card-dia">
                <div class="home-section-title">Seu dia hoje</div>
                <div class="home-section-sub">Resumo rápido do que importa.</div>

                <p><?= $textoFrequencia ?></p>
                <p><?= $textoFaltas ?></p>
                <p><?= $textoAgenda ?></p>

                <?php if (count($itensHoje) > 0): ?>
                    <ul class="home-agenda-list">
                        <?php foreach ($itensHoje as $evento): ?>
                            <li class="home-agenda-item">
                                • <?= htmlspecialchars($evento['descricao'] ?? 'Compromisso') ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>

                <?php if (count($proximosEventos) > 0): ?>
                    <div style="margin-top:10px; font-size:13px; color:var(--texto-secundario);">
                        Próximos dias:
                    </div>
                    <ul class="home-agenda-list">
                        <?php foreach (array_slice($proximosEventos, 0, 3) as $evento): ?>
                            <li class="home-agenda-item">
                                <?= date('d/m', strtotime($evento['data'])) ?> — 
                                <?= htmlspecialchars($evento['descricao'] ?? 'Compromisso') ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </section>

            <!-- RESUMO DA SEMANA -->
            <section class="home-week-card">
                <div class="home-section-title">Resumo da semana</div>
                <div class="home-section-sub">
                    Como está seu ritmo de estudos por enquanto.
                </div>

                <div style="font-size:14px;">
                    Horas de estudo: <strong><?= $horasSemana ?></strong> / <?= $metaHoras ?>h
                </div>
                <div class="progress-wrap">
                    <div class="progress-bar" style="width: <?= $percEstudo ?>%;"></div>
                </div>
                <div class="week-info">
                    Meta cumprida em <strong><?= $percEstudo ?>%</strong>.
                    <?php if ($diasSemana > 0): ?>
                        Você já estudou em <strong><?= $diasSemana ?></strong> dia(s) nesta semana.
                    <?php else: ?>
                        Ainda não tem dias de estudo registrados nessa semana.
                    <?php endif; ?>
                </div>

                <div class="week-info" style="margin-top:8px;">
                    <?php if (!empty($notasBaixas)): ?>
                        Matérias em alerta:
                        <ul class="alerta-lista">
                            <?php foreach ($notasBaixas as $alerta): ?>
                                <li class="alerta-item">
                                    <span class="materia">
                                        <?= htmlspecialchars($alerta['materia'] ?? 'Matéria') ?>
                                    </span> — 
                                    média <span class="nota">
                                        <?= isset($alerta['media']) ? htmlspecialchars($alerta['media']) : '--' ?>
                                    </span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        Nenhuma matéria em alerta cadastrada por enquanto.
                    <?php endif; ?>
                </div>
            </section>

            <!-- NAVEGAÇÃO -->
            <section class="home-nav-card">
                <div class="home-nav-title">Navegar pelo FOAG</div>
                <div class="home-nav-grid">
                    <button class="home-nav-btn" onclick="window.location.href='calendario.php'">
                        <span class="emoji">📅</span>
                        <span>Calendário</span>
                    </button>
                    <button class="home-nav-btn" onclick="window.location.href='tarefas.php'">
                        <span class="emoji">📝</span>
                        <span>Tarefas</span>
                    </button>
                    <button class="home-nav-btn" onclick="window.location.href='notas.php'">
                        <span class="emoji">📊</span>
                        <span>Notas & médias</span>
                    </button>
                    <button class="home-nav-btn" onclick="window.location.href='frequencia.php'">
                        <span class="emoji">✅</span>
                        <span>Frequência</span>
                    </button>
                    <button class="home-nav-btn" onclick="window.location.href='perfil.php'">
                        <span class="emoji">👤</span>
                        <span>Meu perfil</span>
                    </button>
                    <button class="home-nav-btn" onclick="window.location.href='fogi.php'">
                        <span class="emoji">😼</span>
                        <span>FOGi (IA)</span>
                    </button>
                </div>
            </section>

        </div>
    </div>
</main>

<footer>
    FOAG — foco, organização e boas notas.
</footer>

</body>
</html>
