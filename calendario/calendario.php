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

  <script>
  // ========= Expandir mês (seu código) =========
  document.querySelectorAll('.mes').forEach(mes => {
    mes.addEventListener('click', () => {
      const aberto = document.querySelector('.mes.expanded');
      if (aberto && aberto !== mes) aberto.classList.remove('expanded');
      mes.classList.add('expanded');

      if (!mes.querySelector('.fechar-btn')) {
        const fechar = document.createElement('button');
        fechar.textContent = '×';
        fechar.classList.add('fechar-btn');
        fechar.onclick = e => {
          e.stopPropagation();
          mes.classList.remove('expanded');
          // limpa seleção de cor local
          mes.__corSelecionada = null;
          atualizarBotoesCor(mes);
        };
        mes.appendChild(fechar);
      }
    });
  });

  // ========= Seleção de cor AGORA por mês =========
  document.querySelectorAll('.mes').forEach(mes=>{
    mes.__corSelecionada = null;
    const botoesCor = mes.querySelectorAll('.btn-cor');

    function atualizarBotoesCorLocal(){
      botoesCor.forEach(botao=>{
        if (botao.dataset.cor === mes.__corSelecionada){
          botao.style.outline = '3px solid #555';
          botao.style.transform = 'scale(1.3)';
        } else {
          botao.style.outline = 'none';
          botao.style.transform = 'scale(1)';
        }
      });
    }
    mes.__atualizarBotoesCor = atualizarBotoesCorLocal; // para fechar usar

    botoesCor.forEach(botao=>{
      botao.addEventListener('click', e=>{
        e.stopPropagation();
        const cor = botao.dataset.cor;
        mes.__corSelecionada = (mes.__corSelecionada === cor ? null : cor);
        atualizarBotoesCorLocal();
      });
    });
  });
  function atualizarBotoesCor(mes){ mes?.__atualizarBotoesCor?.(); }

  // ========= Clique nos dias (respeita feriado) + Dots + Métricas =========
  document.querySelectorAll('.mes').forEach(mes=>{
    mes.addEventListener('click', e=>{
      if (!mes.classList.contains('expanded')) return;
      if (!mes.__corSelecionada) return;

      const t = e.target.closest?.('.dia');
      if (!t || t.classList.contains('header-dia') || t.textContent.trim()==='') return;

      if (t.classList.contains('feriado')) {
        alert('Este dia é feriado automático e não pode ser alterado.');
        return;
      }
      t.classList.remove('vermelho','amarelo','sem-aula','roxo');
      if (mes.__corSelecionada !== 'limpar'){
        t.classList.add(mes.__corSelecionada);
      }
      // após aplicar cor, atualiza visual
      setTimeout(()=>{
        atualizarDots(t);
        recalcularMetricasDoMes(mes);
      },0);
    });
  });

  // ========= Tooltips e Dots =========
  function atualizarDots(diaEl){
    const dots = diaEl.querySelector('.dots');
    if(!dots) return;
    dots.innerHTML = '';
    if (diaEl.classList.contains('vermelho')) dots.appendChild(criaDot('vermelho'));
    if (diaEl.classList.contains('amarelo')) dots.appendChild(criaDot('amarelo'));
    if (diaEl.classList.contains('sem-aula')) dots.appendChild(criaDot('semaula'));
    if (diaEl.classList.contains('roxo'))     dots.appendChild(criaDot('roxo'));
  }
  function criaDot(tipo){
    const s = document.createElement('span');
    s.className = `dot ${tipo}`;
    return s;
  }

  // ========= Métricas + metas (por mês) =========
  function recalcularMetricasDoMes(mes){
    const dias = [...mes.querySelectorAll('.dia')].filter(d=>!d.classList.contains('header-dia') && d.querySelector('.num-dia'));
    let pres=0, falt=0, atest=0, sem=0, provas=0, totalValidos=0;

    dias.forEach(d=>{
      if (d.classList.contains('sem-aula')) { sem++; return; }
      if (!d.classList.contains('feriado')) totalValidos++;

      if (d.classList.contains('vermelho')) falt++;
      if (d.classList.contains('amarelo')) atest++;
      if (d.classList.contains('roxo'))     provas++;

      const marcado = d.classList.contains('vermelho') || d.classList.contains('amarelo');
      const feriado = d.classList.contains('feriado');
      if (!marcado && !feriado) pres++;
    });

    const metaInput  = mes.querySelector('.meta-presenca');
    const progress   = mes.querySelector('.progress-bar');
    const label      = mes.querySelector('.label-presenca');

    mes.querySelector('.count-presenca').textContent = pres;
    mes.querySelector('.count-falta').textContent    = falt;
    mes.querySelector('.count-atestado').textContent = atest;
    mes.querySelector('.count-semaula').textContent  = sem;
    mes.querySelector('.count-prova').textContent    = provas;

    const meta = clamp(parseInt(metaInput?.value||'80',10),0,100);
    const percPres = totalValidos>0 ? Math.round((pres/totalValidos)*100) : 0;
    if (progress) progress.style.width = Math.min(100, Math.round((percPres/meta)*100)) + '%';
    if (label)    label.textContent = `${percPres}%`;

    // opcional: snapshot localStorage por ano+mes
    const ano = mes.dataset.ano, idx = mes.dataset.mes;
    localStorage.setItem(`foag_meta_${ano}_${idx}`, JSON.stringify({ meta, percPres, pres, falt, atest, sem, provas }));
  }
  function clamp(n,min,max){ return Math.max(min, Math.min(max,n)); }

  // meta change
  document.querySelectorAll('.mes .meta-presenca').forEach(inp=>{
    inp.addEventListener('change', e=>{
      const mes = e.target.closest('.mes');
      recalcularMetricasDoMes(mes);
    });
  });

  // quando um mês expande, calcula
  document.querySelectorAll('.mes').forEach(m=>{
    m.addEventListener('click', ()=> setTimeout(()=>recalcularMetricasDoMes(m), 50));
  });

  // ========= Mini-agenda (dentro do mês) =========
  document.querySelectorAll('.mes .dia').forEach(d=>{
    d.addEventListener('dblclick', e=>{
      const mes = d.closest('.mes');
      const box = mes.querySelector('.mini-agenda');
      const dataEl = mes.querySelector('.mini-agenda .agenda-data');
      const notas  = mes.querySelector('.mini-agenda .agenda-notas');

      const iso = d.getAttribute('data-date');
      if(!iso) return;

      dataEl.textContent = formataDataBR(iso);
      notas.value = localStorage.getItem(chaveAgenda(iso)) || '';
      box.classList.add('aberto');
      notas.focus();
      e.stopPropagation();
    });
  });
  document.querySelectorAll('.mes .agenda-fechar').forEach(btn=>{
    btn.addEventListener('click', e=>{
      e.preventDefault();
      const mes = e.target.closest('.mes');
      mes.querySelector('.mini-agenda').classList.remove('aberto');
    });
  });
  document.querySelectorAll('.mes .agenda-salvar').forEach(btn=>{
    btn.addEventListener('click', e=>{
      e.preventDefault();
      const mes = e.target.closest('.mes');
      const dataBr = mes.querySelector('.mini-agenda .agenda-data').textContent;
      const notas  = mes.querySelector('.mini-agenda .agenda-notas').value;

      const [d,m,y] = dataBr.split('/').map(Number);
      const iso = `${y}-${String(m).padStart(2,'0')}-${String(d).padStart(2,'0')}`;
      localStorage.setItem(chaveAgenda(iso), notas);
      mes.querySelector('.mini-agenda').classList.remove('aberto');
    });
  });
  function chaveAgenda(iso){ return `foag_agenda_${iso}`; }
  function formataDataBR(iso){
    const [y,m,d] = iso.split('-').map(Number);
    return `${String(d).padStart(2,'0')}/${String(m).padStart(2,'0')}/${y}`;
    }

  // ========= Exportar / Imprimir POR MÊS =========
  document.querySelectorAll('.mes .btn-imprimir').forEach(btn=>{
    btn.addEventListener('click', e=>{
      e.preventDefault();
      const mes = e.target.closest('.mes');
      // imprime só o mês se ele estiver expandido; se não, expande e imprime
      if (!mes.classList.contains('expanded')) mes.classList.add('expanded');
      window.print();
    });
  });

  document.querySelectorAll('.mes .btn-exportar-png').forEach(btn=>{
    btn.addEventListener('click', async e=>{
      e.preventDefault();
      const mes = e.target.closest('.mes');
      const bloco = mes.querySelector('.calendario-mes');
      if(!bloco) return;
      const ano = mes.dataset.ano;
      const idx = mes.dataset.mes;
      const nomes = ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'];
      const nomeMes = nomes[parseInt(idx,10)-1] || idx;

      const canvas = await html2canvas(bloco, {useCORS:true, backgroundColor:'#ffffff', scale:2});
      const link = document.createElement('a');
      link.download = `Calendario_${nomeMes}_${ano}.png`;
      link.href = canvas.toDataURL('image/png');
      link.click();
    });
  });

  // ========= Histórico (seletor de ano DENTRO do mês) =========
  document.querySelectorAll('.mes .anoSelect').forEach(sel=>{
    const mes = sel.closest('.mes');
    const anoAtual = parseInt(new URLSearchParams(location.search).get('ano') || new Date().getFullYear(), 10);
    for(let a=anoAtual-4; a<=anoAtual+4; a++){
      const op = document.createElement('option');
      op.value = a; op.textContent = a;
      if (a === anoAtual) op.selected = true;
      sel.appendChild(op);
    }
    sel.addEventListener('change', ()=>{
      const url = new URL(location.href);
      url.searchParams.set('ano', sel.value);
      location.href = url.toString();
    });
  });

  // ========= Inicialização (dots + métricas) =========
  window.addEventListener('load', ()=>{
    document.querySelectorAll('.calendario .dia').forEach(atualizarDots);
    const primeiro = document.querySelector('.mes');
    if (primeiro) recalcularMetricasDoMes(primeiro);
  });

  // ========= Header ícones (seu código) =========
  document.getElementById('icon-perfil').addEventListener('click', () => {
    window.location.href = '../perfil/perfil.php';
  });

  const logoutModal = document.getElementById('logout-modal');
  const confirmLogout = document.getElementById('confirm-logout');
  const cancelLogout = document.getElementById('cancel-logout');

  document.getElementById('icon-sair').addEventListener('click', () => {
    logoutModal.style.display = 'flex';
  });
  confirmLogout.addEventListener('click', () => {
    window.location.href = '../index/index.php';
  });
  cancelLogout.addEventListener('click', () => {
    logoutModal.style.display = 'none';
  });
  logoutModal.addEventListener('click', e => {
    if (e.target === logoutModal) logoutModal.style.display = 'none';
  });
  </script>
</body>
</html>
