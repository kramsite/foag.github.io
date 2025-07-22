<?php
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
    return $dias;
}

// Gera o calendário completo (todos os meses)
function gerarCalendario() {
    $ano = date('Y');
    $meses = ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'];
    $diasSemana = ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb'];

    foreach ($meses as $mes) {
        $dias = obterDiasDoMes($mes, $ano);
        echo "<div class='mes'>";
        echo "<div class='calendario-mes'>";
        echo "<div class='header-mes'>$mes</div>";
        echo "<div class='dias'>";
        foreach ($diasSemana as $dia) {
            echo "<div class='dia header-dia'><strong>$dia</strong></div>"; // Cabeçalho (não clicável)
        }
        foreach ($dias as $d) {
            echo "<div class='dia'>".($d ?: '')."</div>";
        }
        echo "</div></div>";
        // Coluna da direita (informações/agenda)
        echo "<div class='info-mes'>";
       echo "<p>Selecione a cor e depois clique no dia:</p>";
       echo "<div class='botoes-cores'>";
       echo "<button class='btn-cor' data-cor='vermelho' title='Faltou (sem atestado)' style='background:#e74c3c'></button>";
       echo "<button class='btn-cor' data-cor='amarelo' title='Faltou com atestado' style='background:#f1c40f'></button>";
       echo "<button class='btn-cor' data-cor='azul' title='Feriado / sem aula' style='background:#3498db'></button>";
       echo "<button class='btn-cor' data-cor='roxo' title='Dia de prova' style='background:#8e44ad'></button>";
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
    <link rel="stylesheet" href="estilo.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
</head>
<body>
    <header class="cabecalho">FOAG</header>
    <div class="container">
        <nav class="menu">
            <a href="../inicio/inicio.html">Início</a>
            <a href="../agenda/agenda.html">Agenda</a>
            <a href="../calendario/calendario.php">Calendário</a>
            <a href="../perfil/perfil.php">Perfil</a>
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
            if (target.classList.contains('dia') && !target.classList.contains('header-dia') && target.textContent.trim() !== '') {
                // Remove as classes de cor antigas
                target.classList.remove('vermelho', 'amarelo', 'azul', 'roxo');
                // Aplica a cor selecionada
                target.classList.add(corSelecionada);
            }
        });
    });
</script>

</body>
</html>
