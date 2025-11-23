// calend.js — FOAG

document.addEventListener('DOMContentLoaded', () => {
  // ---------------- AGENDA (integração com agenda.php) ----------------
  const agendaData = window.CAL_AGENDA_DATA || {
    notas: [],
    tarefas: [],
    nao_esquecer: []
  };
  const AGENDA_SAVE_URL = window.CAL_AGENDA_SAVE_URL || '../bloco/salvar_agenda.php';

  function salvarAgendaServidor() {
    try {
      fetch(AGENDA_SAVE_URL, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(agendaData)
      })
        .then(res => res.text())
        .then(txt => {
          console.log('Agenda salva via calendário:', txt);
        })
        .catch(err => console.error('Erro ao salvar agenda via calendário:', err));
    } catch (e) {
      console.error('Erro fetch agenda:', e);
    }
  }

  // retorna TODAS as tarefas daquele dia (independente da origem)
  function tarefasDoDia(iso) {
    const lista = Array.isArray(agendaData.tarefas) ? agendaData.tarefas : [];
    return lista.filter(t => t.data === iso && t.texto && t.texto.trim() !== '');
  }

  // salva / atualiza tarefa desse dia com origem "calendario"
  function salvarTextoDoDiaNaAgenda(iso, texto) {
    if (!Array.isArray(agendaData.tarefas)) {
      agendaData.tarefas = [];
    }

    // remove tarefas desse dia criadas pelo calendário (pra não duplicar)
    agendaData.tarefas = agendaData.tarefas.filter(
      t => !(t.data === iso && t.origem === 'calendario')
    );

    const txt = (texto || '').trim();
    if (txt !== '') {
      agendaData.tarefas.push({
        texto: txt,
        data: iso,
        origem: 'calendario'
      });
    }

    salvarAgendaServidor();
  }

  // marca .has-tarefa e adiciona dot azul nos dias com tarefas da Agenda
  function marcarDiasComTarefa() {
    if (!agendaData || !Array.isArray(agendaData.tarefas)) return;

    agendaData.tarefas.forEach(t => {
      const iso = t.data;
      if (!iso) return;
      const diaEl = document.querySelector(`.calendario .dia[data-date="${iso}"]`);
      if (diaEl) {
        diaEl.classList.add('has-tarefa');
        atualizarDots(diaEl);
      }
    });
  }

  // ---------------- UTIL: formatação data ----------------
  function formataDataBR(iso) {
    const [y, m, d] = iso.split('-').map(Number);
    return `${String(d).padStart(2, '0')}/${String(m).padStart(2, '0')}/${y}`;
  }

  // ---------------- UTIL: fechar mês ----------------
  function fecharMes(mes) {
    if (!mes) return;
    mes.classList.remove('expanded');
    mes.__corSelecionada = null;
    mes?.__atualizarBotoesCor?.();

    if (!document.querySelector('.mes.expanded')) {
      document.body.classList.remove('no-scroll');
      document.getElementById('cal-backdrop')?.classList.remove('ativo');
    }
  }

  // ---------------- EXPANDIR MÊS (card → tela cheia) ----------------
  document.querySelectorAll('.mes').forEach(mes => {
    mes.addEventListener('click', () => {
      const aberto = document.querySelector('.mes.expanded');
      if (aberto && aberto !== mes) return;

      if (!mes.classList.contains('expanded')) {
        mes.classList.add('expanded');
        document.body.classList.add('no-scroll');
        document.getElementById('cal-backdrop')?.classList.add('ativo');

        if (!mes.querySelector('.fechar-btn')) {
          const fechar = document.createElement('button');
          fechar.textContent = '×';
          fechar.classList.add('fechar-btn');
          fechar.onclick = e => {
            e.stopPropagation();
            fecharMes(mes);
          };
          mes.appendChild(fechar);
        }
      }
    });
  });

  // ---------------- SELEÇÃO DE COR ----------------
  document.querySelectorAll('.mes').forEach(mes => {
    mes.__corSelecionada = null;
    const botoesCor = mes.querySelectorAll('.btn-cor');

    function atualizarBotoesCorLocal() {
      botoesCor.forEach(botao => {
        if (botao.dataset.cor === mes.__corSelecionada) {
          botao.classList.add('selecionado');
          botao.style.outline = '3px solid #555';
          botao.style.transform = 'scale(1.3)';
        } else {
          botao.classList.remove('selecionado');
          botao.style.outline = 'none';
          botao.style.transform = 'scale(1)';
        }
      });
    }

    mes.__atualizarBotoesCor = atualizarBotoesCorLocal;

    botoesCor.forEach(botao => {
      botao.addEventListener('click', e => {
        e.stopPropagation();
        const cor = botao.dataset.cor;

        botoesCor.forEach(b => b.classList.remove('selecionado'));

        if (mes.__corSelecionada === cor) {
          mes.__corSelecionada = null;
        } else {
          mes.__corSelecionada = cor;
          botao.classList.add('selecionado');
        }

        atualizarBotoesCorLocal();
      });
    });
  });

  // ---------------- CLIQUE NOS DIAS → pintar com cor ----------------
  document.querySelectorAll('.mes').forEach(mes => {
    mes.addEventListener('click', e => {
      if (!mes.classList.contains('expanded')) return;
      if (!mes.__corSelecionada) return;

      const t = e.target.closest?.('.dia');
      if (!t || t.classList.contains('header-dia') || t.textContent.trim() === '') return;

      if (t.classList.contains('feriado')) {
        alert('Este dia é feriado automático e não pode ser alterado.');
        return;
      }

      t.classList.remove('vermelho', 'amarelo', 'sem-aula', 'roxo');

      if (mes.__corSelecionada !== 'limpar') {
        t.classList.add(mes.__corSelecionada);
      }

      setTimeout(() => {
        atualizarDots(t);
        recalcularMetricasDoMes(mes);
      }, 0);
    });
  });

  // ---------------- DOTS (bolinhas) ----------------
  function criaDot(tipo) {
    const s = document.createElement('span');
    s.className = `dot ${tipo}`;
    return s;
  }

  function atualizarDots(diaEl) {
    const dots = diaEl.querySelector('.dots');
    if (!dots) return;
    dots.innerHTML = '';

    if (diaEl.classList.contains('vermelho'))  dots.appendChild(criaDot('vermelho'));
    if (diaEl.classList.contains('amarelo'))   dots.appendChild(criaDot('amarelo'));
    if (diaEl.classList.contains('sem-aula'))  dots.appendChild(criaDot('semaula'));
    if (diaEl.classList.contains('roxo'))      dots.appendChild(criaDot('roxo'));

    // pontinho azul escuro se tiver tarefa
    if (diaEl.classList.contains('has-tarefa')) {
      dots.appendChild(criaDot('tarefa'));
    }
  }

  // ---------------- MÉTRICAS / METAS ----------------
  function clamp(n, min, max) {
    return Math.max(min, Math.min(max, n));
  }

  function recalcularMetricasDoMes(mes) {
    const dias = [...mes.querySelectorAll('.dia')].filter(
      d => !d.classList.contains('header-dia') && d.querySelector('.num-dia')
    );

    let pres = 0, falt = 0, atest = 0, sem = 0, provas = 0, totalValidos = 0;

    dias.forEach(d => {
      if (d.classList.contains('sem-aula')) { sem++; return; }
      if (!d.classList.contains('feriado')) totalValidos++;

      if (d.classList.contains('vermelho')) falt++;
      if (d.classList.contains('amarelo')) atest++;
      if (d.classList.contains('roxo'))    provas++;

      const marcado = d.classList.contains('vermelho') || d.classList.contains('amarelo');
      const feriado = d.classList.contains('feriado');
      if (!marcado && !feriado) pres++;
    });

    const metaInput = mes.querySelector('.meta-presenca');
    const progress  = mes.querySelector('.progress-bar');
    const label     = mes.querySelector('.label-presenca');

    mes.querySelector('.count-presenca').textContent  = pres;
    mes.querySelector('.count-falta').textContent     = falt;
    mes.querySelector('.count-atestado').textContent  = atest;
    mes.querySelector('.count-semaula').textContent   = sem;
    mes.querySelector('.count-prova').textContent     = provas;

    const meta = clamp(parseInt(metaInput?.value || '80', 10), 0, 100);
    const percPres = totalValidos > 0 ? Math.round((pres / totalValidos) * 100) : 0;

    if (progress) progress.style.width = Math.min(100, Math.round((percPres / meta) * 100)) + '%';
    if (label)    label.textContent = `${percPres}%`;

    const ano = mes.dataset.ano;
    const idx = mes.dataset.mes;
    localStorage.setItem(
      `foag_meta_${ano}_${idx}`,
      JSON.stringify({ meta, percPres, pres, falt, atest, sem, provas })
    );
  }

  document.querySelectorAll('.mes .meta-presenca').forEach(inp => {
    inp.addEventListener('change', e => {
      const mes = e.target.closest('.mes');
      recalcularMetricasDoMes(mes);
    });
  });

  // ---------------- MINI-AGENDA (Ver tarefas / Agendar nova) ----------------
  // Clique no dia (sem cor selecionada) → abre mini-agenda com botões
  document.querySelectorAll('.mes .dia').forEach(diaEl => {
    diaEl.addEventListener('click', e => {
      const mes = diaEl.closest('.mes');
      if (!mes || !mes.classList.contains('expanded')) return;
      if (mes.__corSelecionada) return; // se tiver cor selecionada, o handler de cor cuida
      if (diaEl.classList.contains('header-dia')) return;

      const iso = diaEl.getAttribute('data-date');
      if (!iso) return;

      e.stopPropagation();

      const mini   = mes.querySelector('.mini-agenda');
      const dataEl = mini?.querySelector('.agenda-data');
      const resumo = mini?.querySelector('.agenda-resumo');
      const editor = mini?.querySelector('.agenda-editor');
      const notas  = mini?.querySelector('.agenda-notas');
      const btnVer  = mini?.querySelector('.btn-ver-tarefas');
      const btnNova = mini?.querySelector('.btn-nova-tarefa');

      if (!mini || !dataEl || !resumo || !editor || !notas || !btnVer || !btnNova) return;

      mini.dataset.date = iso;
      dataEl.textContent = formataDataBR(iso);

      // sempre começa com tudo escondido
      resumo.style.display = 'none';
      editor.style.display = 'none';

      // configura botões
      btnVer.onclick = () => {
        const ts = tarefasDoDia(iso);
        if (!ts.length) {
          resumo.innerHTML = `<p class="agenda-resumo-vazio">Nenhuma tarefa cadastrada para este dia.</p>`;
        } else {
          resumo.innerHTML = `
            <div class="agenda-bloco">
              <strong>Tarefas do dia</strong>
              <ul>${ts.map(t => `<li>${t.texto}</li>`).join('')}</ul>
            </div>
          `;
        }
        resumo.style.display = 'block';
        editor.style.display = 'none';
      };

      btnNova.onclick = () => {
        const lista = tarefasDoDia(iso);
        const tCal  = lista.find(t => t.origem === 'calendario');
        notas.value = tCal ? (tCal.texto || '') : '';
        editor.style.display = 'block';
        resumo.style.display = 'none';
        notas.focus();
      };

      // comportamento padrão: se já tem tarefa → começa em "ver tarefas"; senão → editor
      if (tarefasDoDia(iso).length) {
        btnVer.click();
      } else {
        btnNova.click();
      }

      mini.classList.add('aberto');
    });
  });

  // Botão fechar da mini-agenda
  document.querySelectorAll('.mes .agenda-fechar').forEach(btn => {
    btn.addEventListener('click', e => {
      e.preventDefault();
      const mes = e.target.closest('.mes');
      const box = mes.querySelector('.mini-agenda');
      box.classList.remove('aberto');
    });
  });

  // Salvar texto da mini-agenda → vira tarefa na Agenda
  document.querySelectorAll('.mes .agenda-salvar').forEach(btn => {
    btn.addEventListener('click', e => {
      e.preventDefault();
      const mes  = e.target.closest('.mes');
      const box  = mes.querySelector('.mini-agenda');
      const notasEl = box?.querySelector('.agenda-notas');
      const iso  = box?.dataset.date;

      if (!iso || !notasEl) {
        box?.classList.remove('aberto');
        return;
      }

      salvarTextoDoDiaNaAgenda(iso, notasEl.value);

      // se salvou texto, garante que o dia tenha a bolinha azul
      const diaEl = document.querySelector(`.calendario .dia[data-date="${iso}"]`);
      if (diaEl) {
        if ((notasEl.value || '').trim() === '') {
          diaEl.classList.remove('has-tarefa');
        } else {
          diaEl.classList.add('has-tarefa');
        }
        atualizarDots(diaEl);
      }

      box.classList.remove('aberto');
    });
  });

  // ---------------- EXPORTAR / IMPRIMIR ----------------
  document.querySelectorAll('.mes .btn-imprimir').forEach(btn => {
    btn.addEventListener('click', e => {
      e.preventDefault();
      const mes = e.target.closest('.mes');
      if (!mes.classList.contains('expanded')) {
        mes.classList.add('expanded');
        document.body.classList.add('no-scroll');
        document.getElementById('cal-backdrop')?.classList.add('ativo');

        if (!mes.querySelector('.fechar-btn')) {
          const fechar = document.createElement('button');
          fechar.textContent = '×';
          fechar.classList.add('fechar-btn');
          fechar.onclick = ev => {
            ev.stopPropagation();
            fecharMes(mes);
          };
          mes.appendChild(fechar);
        }
      }
      window.print();
    });
  });

  document.querySelectorAll('.mes .btn-exportar-png').forEach(btn => {
    btn.addEventListener('click', async e => {
      e.preventDefault();
      const mes = e.target.closest('.mes');
      const bloco = mes.querySelector('.calendario-mes');
      if (!bloco) return;

      const ano = mes.dataset.ano;
      const idx = mes.dataset.mes;
      const nomes = ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];
      const nomeMes = nomes[parseInt(idx, 10) - 1] || idx;

      if (typeof html2canvas !== 'function') {
        console.warn('html2canvas não carregado.');
        return;
      }

      const canvas = await html2canvas(bloco, {
        useCORS: true,
        backgroundColor: '#ffffff',
        scale: 2
      });

      const link = document.createElement('a');
      link.download = `Calendario_${nomeMes}_${ano}.png`;
      link.href = canvas.toDataURL('image/png');
      link.click();
    });
  });

  // ---------------- SELETOR DE ANO ----------------
  document.querySelectorAll('.mes .anoSelect').forEach(sel => {
    const urlParams = new URLSearchParams(location.search);
    const anoAtual = parseInt(urlParams.get('ano') || new Date().getFullYear(), 10);

    for (let a = anoAtual - 4; a <= anoAtual + 4; a++) {
      const op = document.createElement('option');
      op.value = a;
      op.textContent = a;
      if (a === anoAtual) op.selected = true;
      sel.appendChild(op);
    }

    sel.addEventListener('change', () => {
      const url = new URL(location.href);
      url.searchParams.set('ano', sel.value);
      location.href = url.toString();
    });
  });

  // ---------------- GARANTIR VISIBILIDADE METAS QUANDO ABRIR MÊS ----------------
  function verificarVisibilidadeMeta(mes) {
    const metas = mes.querySelector('.painel-metas');
    if (!metas) return;
    metas.style.display = 'flex';
    metas.style.visibility = 'visible';
    metas.style.opacity = '1';
  }

  // ---------------- INICIALIZAÇÃO (dots + metas + tarefas) ----------------
  // bolinhas pra tudo que já tem classe (vermelho/amarelo/roxo/etc)
  document.querySelectorAll('.calendario .dia').forEach(atualizarDots);

  // agora marca dias com tarefas vindas da Agenda (pontinho azul já aparece)
  marcarDiasComTarefa();

  document.querySelectorAll('.mes').forEach(mes => {
    recalcularMetricasDoMes(mes);

    mes.addEventListener('click', () => {
      setTimeout(() => {
        if (mes.classList.contains('expanded')) {
          verificarVisibilidadeMeta(mes);
        }
      }, 80);
    });
  });

  // ---------------- ÍCONES HEADER (PERFIL / LOGOUT) ----------------
  const perfilIcon = document.getElementById('icon-perfil');
  if (perfilIcon) {
    perfilIcon.addEventListener('click', () => {
      window.location.href = '../perfil/perfil.php';
    });
  }

  const logoutModal   = document.getElementById('logout-modal');
  const iconSair      = document.getElementById('icon-sair');
  const confirmLogout = document.getElementById('confirm-logout');
  const cancelLogout  = document.getElementById('cancel-logout');

  if (iconSair && logoutModal) {
    iconSair.addEventListener('click', () => {
      logoutModal.style.display = 'flex';
    });
  }

  if (confirmLogout) {
    confirmLogout.addEventListener('click', () => {
      window.location.href = '../index/index.php';
    });
  }

  if (cancelLogout && logoutModal) {
    cancelLogout.addEventListener('click', () => {
      logoutModal.style.display = 'none';
    });

    logoutModal.addEventListener('click', e => {
      if (e.target === logoutModal) logoutModal.style.display = 'none';
    });
  }

  // ---------------- MODAL FOGi ----------------
  const fogiBtn   = document.getElementById('icon-fogi');
  const fogiModal = document.getElementById('fogi-modal');
  const fogiFrame = document.getElementById('fogi-iframe');
  const fogiClose = document.getElementById('fogi-close');

  if (fogiBtn && fogiModal && fogiFrame && fogiClose) {
    fogiBtn.addEventListener('click', () => {
      fogiFrame.src = 'http://127.0.0.1:5000'; // Flask/Ollama
      fogiModal.style.display = 'flex';
      document.body.style.overflow = 'hidden';
    });

    fogiClose.addEventListener('click', () => {
      fogiModal.style.display = 'none';
      fogiFrame.src = 'about:blank';
      document.body.style.overflow = '';
    });

    window.addEventListener('message', ev => {
      if (ev.data && ev.data.type === 'FOGI_CLOSE') {
        fogiModal.style.display = 'none';
        fogiFrame.src = 'about:blank';
        document.body.style.overflow = '';
      }
    });
  }
});
