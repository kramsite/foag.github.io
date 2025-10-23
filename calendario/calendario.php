<?php
// Carrega os feriados do JSON
$feriados = json_decode(file_get_contents(__DIR__ . '/../json/feriados.json'), true);

// Função para gerar os dias de cada mês
function obterDiasDoMes($mes, $ano) {
    $meses = [
        'Janeiro' => 1, 'Fevereiro' => 2, 'Março' => 3, 'Abril' => 4,
        'Maio' => 5, 'Junho' => 6, 'Julho' => 7, 'Agosto' => 8,
        'Setembro' => 9, 'Outubro' => 10, 'Novembro' => 11, 'Dezembro' => 12
    ];
    $numeroMes = $meses[$mes];
    $diasNoMes = cal_days_in_month(CAL_GREGORIAN, $numeroMes, $ano);
    $primeiroDiaSemana = date('w', strtotime("$ano-$numeroMes-01"));

    $dias = [];
    for ($i = 0; $i < $primeiroDiaSemana; $i++) $dias[] = '';
    for ($i = 1; $i <= $diasNoMes; $i++) $dias[] = $i;
    return [$dias, $numeroMes];
}

// Gera o calendário completo (todos os meses)
function gerarCalendario() {
    global $feriados;
    // Histórico por ano via ?ano=YYYY
    $ano = isset($_GET['ano']) ? (int)$_GET['ano'] : (int)date('Y');

    $meses = ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'];
    $diasSemana = ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb'];

    foreach ($meses as $mes) {
        list($dias, $numeroMes) = obterDiasDoMes($mes, $ano);

        echo "<div class='mes' data-ano='$ano' data-mes='$numeroMes'>";
        echo "  <div class='calendario-mes'>";
        echo "    <div class='header-mes'>$mes</div>";
        echo "    <div class='dias'>";

        foreach ($diasSemana as $dia) {
            echo "<div class='dia header-dia'><strong>$dia</strong></div>";
        }

        foreach ($dias as $d) {
            if ($d) {
                $dataAtual = sprintf('%04d-%02d-%02d', $ano, $numeroMes, $d);
                $classeExtra = '';
                $attrExtra = '';
                if (isset($feriados[$dataAtual])) {
                    // marca feriado + nome no data-attribute (tooltip)
                    $classeExtra = 'feriado';
                    $nomeFeriado = htmlspecialchars($feriados[$dataAtual], ENT_QUOTES, 'UTF-8');
                    $attrExtra = " data-feriado=\"$nomeFeriado\"";
                }
                echo "<div class='dia $classeExtra'$attrExtra data-date='$dataAtual'>
                        <span class='num-dia'>$d</span>
                        <div class='dots'></div>
                      </div>";
            } else {
                echo "<div class='dia'></div>";
            }
        }

        echo "    </div>"; // .dias
        echo "  </div>";   // .calendario-mes

        // TUDO DENTRO DO MINI CALENDÁRIO (por mês)
        echo "  <div class='info-mes'>";
        echo "    <div class='toolbar-cal'>";
        echo "      <div class='lado-a'>";
        echo "        <label>Ano:</label>";
        echo "        <select class='anoSelect'></select>";
        echo "      </div>";
        echo "      <div class='lado-b'>";
        echo "        <button class='btn-exportar-png' title='Exportar PNG'>Exportar PNG</button>";
        echo "        <button class='btn-imprimir' title='Imprimir mês'>Imprimir</button>";
        echo "      </div>";
        echo "    </div>";

        echo "    <p>Selecione a cor e depois clique no dia:</p>";
        echo "    <div class='botoes-cores'>";
        echo "      <div class='cor-item'><button class='btn-cor' data-cor='vermelho' style='background:#e74c3c'></button><span>Faltou</span></div>";
        echo "      <div class='cor-item'><button class='btn-cor' data-cor='amarelo' style='background:#f1c40f'></button><span>Atestado</span></div>";
        echo "      <div class='cor-item'><button class='btn-cor' data-cor='sem-aula' style='background:#f39c12'></button><span>Sem aula</span></div>";
        echo "      <div class='cor-item'><button class='btn-cor' data-cor='roxo' style='background:#8e44ad'></button><span>Prova</span></div>";
        echo "      <div class='cor-item'><button class='btn-cor limpar' data-cor='limpar' style='background:#bdc3c7'></button><span>Limpar</span></div>";
        echo "    </div>";

        echo "    <div class='painel-metas'>";
        echo "      <div class='linha'>";
        echo "        <label>Meta de presença (%):</label>";
        echo "        <input class='meta-presenca' type='number' min='0' max='100' value='80'>";
        echo "      </div>";
        echo "      <div class='linha linha-progress'>";
        echo "        <div class='progress-wrap'><div class='progress-bar'></div></div>";
        echo "        <span class='label-presenca'>0%</span>";
        echo "      </div>";
        echo "      <div class='resumos'>";
        echo "        <span><b>Presenças</b>: <span class='count-presenca'>0</span></span>";
        echo "        <span><b>Faltas</b>: <span class='count-falta'>0</span></span>";
        echo "        <span><b>Atestados</b>: <span class='count-atestado'>0</span></span>";
        echo "        <span><b>Sem aula</b>: <span class='count-semaula'>0</span></span>";
        echo "        <span><b>Provas</b>: <span class='count-prova'>0</span></span>";
        echo "      </div>";
        echo "    </div>";

        // Mini-agenda embutida no próprio mês
        echo "    <div class='mini-agenda'>";
        echo "      <div class='agenda-header'>";
        echo "        <strong class='agenda-data'></strong>";
        echo "        <button class='agenda-fechar'>×</button>";
        echo "      </div>";
        echo "      <textarea class='agenda-notas' placeholder='Anote tarefas, horários, links...'></textarea>";
        echo "      <button class='agenda-salvar'>Salvar</button>";
        echo "    </div>";

        echo "  </div>"; // .info-mes
        echo "</div>";   // .mes
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Calendário</title>
  <link rel="stylesheet" href="calendario.css">
  <link rel="stylesheet" href="dark_calend.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
  <script src="../m.escuro/dark-mode.js"></script>
  <!-- Export PNG -->
  <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>

</head>
<!-- Backdrop para bloquear interação no fundo quando um mês estiver expandido -->
<div id="cal-backdrop" aria-hidden="true"></div>

<body>
  <header class="cabecalho">
    FOAG
    <div class="header-icons">
      <i id="themeToggle" class="fa-solid fa-moon" title="Modo Escuro"></i>
      <i id="icon-perfil" class="fa-regular fa-user" title="Perfil"></i>
      <i id="icon-sair" class="fa-solid fa-right-from-bracket" title="Sair"></i>
    </div>
  </header>

  <div class="container">
    <nav class="menu">
      <a href="../inicio/inicio.php">Início</a>
      <a href="../agenda/agenda.php">Agenda</a>
      <a href="../HORARIO/horario.php">Horario</a>
      <a href="#">Sobre</a>
      <a href="#">Contato</a>
    </nav>

    <div class="conteudo">
      <div class="calendario-container">
        <div class="calendario">
          <?php gerarCalendario(); ?>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal de Confirmação -->
  <div id="logout-modal" class="modal">
    <div class="modal-content">
      <h3>Ah... já vai?</h3>
      <h4>Tem certeza que deseja sair?</h4>
      <div class="modal-buttons">
        <button id="confirm-logout">Sim</button>
        <button id="cancel-logout">Cancelar</button>
      </div>
    </div>
  </div>

  <footer>&copy; 2025 FOAG. Todos os direitos reservados.</footer>

  <script src="calendario.js"></script>


</body>
</html>
