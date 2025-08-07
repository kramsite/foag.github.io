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
    $ano = date('Y');
    $meses = ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'];
    $diasSemana = ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb'];

    foreach ($meses as $mes) {
        list($dias, $numeroMes) = obterDiasDoMes($mes, $ano);
        echo "<div class='mes'>";
        echo "<div class='calendario-mes'>";
        echo "<div class='header-mes'>$mes</div>";
        echo "<div class='dias'>";

        foreach ($diasSemana as $dia) {
            echo "<div class='dia header-dia'><strong>$dia</strong></div>";
        }

        foreach ($dias as $d) {
            if ($d) {
                $dataAtual = sprintf('%04d-%02d-%02d', $ano, $numeroMes, $d);
                $classeExtra = '';
                if (isset($feriados[$dataAtual])) {
                    $classeExtra = 'azul'; // já marca como feriado
                }
                echo "<div class='dia $classeExtra'>$d</div>";
            } else {
                echo "<div class='dia'></div>";
            }
        }

        echo "</div></div>";
        echo "<div class='info-mes'>";
echo "<p>Selecione a cor e depois clique no dia:</p>";
echo "<div class='botoes-cores'>";
echo "<div class='cor-item'><button class='btn-cor' data-cor='vermelho' style='background:#e74c3c'></button><span>Faltou</span></div>";
echo "<div class='cor-item'><button class='btn-cor' data-cor='amarelo' style='background:#f1c40f'></button><span>Atestado</span></div>";
echo "<div class='cor-item'><button class='btn-cor' data-cor='sem-aula' style='background:#f39c12'></button><span>Sem aula</span></div>";  // substitui azul por sem aula
echo "<div class='cor-item'><button class='btn-cor' data-cor='roxo' style='background:#8e44ad'></button><span>Prova</span></div>";
echo "<div class='cor-item'><button class='btn-cor limpar' data-cor='limpar' style='background:#bdc3c7'></button><span>Limpar</span></div>";
echo "</div>";
echo "</div>";
        echo "</div>";
    }
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendário</title>
    <link rel="stylesheet" href="galinha.css">
    <link rel="stylesheet" href="../m.escuro/dark-mode.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
     <script src="../m.escuro/dark-mode.js"></script>
</head>

<body>
  <header class="cabecalho">
  FOAG
  <div class="header-icons">
    <i id="icon-perfil" class="fa-regular fa-user" title="Perfil"></i>
    <i id="icon-sair" class="fa-solid fa-right-from-bracket" title="Sair"></i>
  </div>
</header>
    <div class="container">
        <nav class="menu">
            <a href="../inicio/inicio.html">Início</a>
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

<button class="theme-toggle" id="themeToggle">🌙</button>

    <footer>&copy; 2025 FOAG. Todos os direitos reservados.</footer>

    <script>
    // Seu código de expandir mês (já existente)
document.querySelectorAll('.mes').forEach(mes => {
    mes.addEventListener('click', () => {
        const aberto = document.querySelector('.mes.expanded');
        if (aberto && aberto !== mes) aberto.classList.remove('expanded');
        mes.classList.add('expanded');

        // Adiciona botão de fechar apenas uma vez
        if (!mes.querySelector('.fechar-btn')) {
            const fechar = document.createElement('button');
            fechar.textContent = '×';
            fechar.classList.add('fechar-btn');
            fechar.onclick = e => {
                e.stopPropagation();
                mes.classList.remove('expanded');
                // Limpa seleção de cor ao fechar
                corSelecionada = null;
                atualizarBotoesCor();
            };
            mes.appendChild(fechar);
        }
    });
});

// Variável para guardar a cor selecionada
let corSelecionada = null;

// Pega todos os botões de cor
const botoesCor = document.querySelectorAll('.btn-cor');

// Marca/desmarca o botão selecionado visualmente
function atualizarBotoesCor() {
    botoesCor.forEach(botao => {
        if (botao.dataset.cor === corSelecionada) {
            botao.style.outline = '3px solid #555';
            botao.style.transform = 'scale(1.3)';
        } else {
            botao.style.outline = 'none';
            botao.style.transform = 'scale(1)';
        }
    });
}

// Evento para clicar no botão de cor
botoesCor.forEach(botao => {
    botao.addEventListener('click', e => {
        e.stopPropagation(); // evita fechar o mês expandido
        const cor = botao.dataset.cor;
        if (corSelecionada === cor) {
            corSelecionada = null; // desmarca se clicar de novo
        } else {
            corSelecionada = cor;
        }
        atualizarBotoesCor();
    });
});

// Evento para clicar nos dias (apenas no mês expandido)
document.querySelectorAll('.mes').forEach(mes => {
    mes.addEventListener('click', e => {
        if (!mes.classList.contains('expanded')) return;
        if (!corSelecionada) return;

        const target = e.target;
        if (
            target.classList.contains('dia') && 
            !target.classList.contains('header-dia') && 
            target.textContent.trim() !== ''
        ) {
            if (target.classList.contains('feriado')) {
                // Dia feriado automático, não permite mudar
                alert('Este dia é feriado automático e não pode ser alterado.');
                return;
            }
            // Remove as classes de cor antigas (exceto 'feriado')
            target.classList.remove('vermelho', 'amarelo', 'sem-aula', 'roxo');

            if (corSelecionada === 'limpar') {
                // Limpa as cores manuais
                return;
            }

            // Aplica a cor selecionada
            target.classList.add(corSelecionada);
        }
    });
});

// Botões do header
document.getElementById('icon-perfil').addEventListener('click', () => {
    window.location.href = '../perfil/perfil.php'; // Redireciona para perfil
});

const logoutModal = document.getElementById('logout-modal');
const confirmLogout = document.getElementById('confirm-logout');
const cancelLogout = document.getElementById('cancel-logout');

// Abrir modal ao clicar no ícone de sair
document.getElementById('icon-sair').addEventListener('click', () => {
  logoutModal.style.display = 'flex';
});

// Botão "Sim" - redireciona
confirmLogout.addEventListener('click', () => {
  window.location.href = '../index/index.php';
});

// Botão "Cancelar" - fecha o modal
cancelLogout.addEventListener('click', () => {
  logoutModal.style.display = 'none';
});

// Fecha o modal se clicar fora dele
logoutModal.addEventListener('click', e => {
  if (e.target === logoutModal) {
    logoutModal.style.display = 'none';
  }
});


</script>

</body>
</html>
