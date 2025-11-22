// calendario.js — FOAG

document.addEventListener('DOMContentLoaded', () => {
  // ========== UTIL: FECHAR MÊS ==========
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

  // ========== EXPANDIR MÊS ==========
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

  // ========== SELEÇÃO DE COR ==========
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

  // ========== CLIQUE NOS DIAS ==========
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

  // ========== DOTS ==========
  function atualizarDots(diaEl) {
    const dots = diaEl.querySelector('.dots');
    if (!dots) return;
    dots.innerHTML = '';

    if (diaEl.classList.contains('vermelho')) dots.appendChild(criaDot('vermelho'));
    if (diaEl.classList.contains('amarelo')) dots.appendChild(criaDot('amarelo'));
    if (diaEl.classList.contains('sem-aula')) dots.appendChild(criaDot('semaula'));
    if (diaEl.classList.contains('roxo')) dots.appendChild(criaDot('roxo'));
  }

  function criaDot(tipo) {
    const s = document.createElement('span');
    s.className = `dot ${tipo}`;
    return s;
  }

  // ========== MÉTRICAS / METAS ==========
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
      if (d.classList.contains('roxo')) provas++;

      const marcado = d.classList.contains('vermelho') || d.classList.contains('amarelo');
      const feriado = d.classList.contains('feriado');
      if (!marcado && !feriado) pres++;
    });

    const metaInput = mes.querySelector('.meta-presenca');
    const progress = mes.querySelector('.progress-bar');
    const label = mes.querySelector('.label-presenca');

    mes.querySelector('.count-presenca').textContent = pres;
    mes.querySelector('.count-falta').textContent = falt;
    mes.querySelector('.count-atestado').textContent = atest;
    mes.querySelector('.count-semaula').textContent = sem;
    mes.querySelector('.count-prova').textContent = provas;

    const meta = clamp(parseInt(metaInput?.value || '80', 10), 0, 100);
    const percPres = totalValidos > 0 ? Math.round((pres / totalValidos) * 100) : 0;

    if (progress) progress.style.width = Math.min(100, Math.round((percPres / meta) * 100)) + '%';
    if (label) label.textContent = `${percPres}%`;

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

  // ========== MINI-AGENDA ==========
  function chaveAgenda(iso) {
    return `foag_agenda_${iso}`;
  }

  function formataDataBR(iso) {
    const [y, m, d] = iso.split('-').map(Number);
    return `${String(d).padStart(2, '0')}/${String(m).padStart(2, '0')}/${y}`;
  }

  document.querySelectorAll('.mes .dia').forEach(d => {
    d.addEventListener('dblclick', e => {
      const mes = d.closest('.mes');
      if (!mes.classList.contains('expanded')) return;

      const box = mes.querySelector('.mini-agenda');
      const dataEl = mes.querySelector('.mini-agenda .agenda-data');
      const notas = mes.querySelector('.mini-agenda .agenda-notas');

      const iso = d.getAttribute('data-date');
      if (!iso) return;

      dataEl.textContent = formataDataBR(iso);
      notas.value = localStorage.getItem(chaveAgenda(iso)) || '';
      box.classList.add('aberto');
      notas.focus();
      e.stopPropagation();
    });
  });

  document.querySelectorAll('.mes .agenda-fechar').forEach(btn => {
    btn.addEventListener('click', e => {
      e.preventDefault();
      const mes = e.target.closest('.mes');
      mes.querySelector('.mini-agenda').classList.remove('aberto');
    });
  });

  document.querySelectorAll('.mes .agenda-salvar').forEach(btn => {
    btn.addEventListener('click', e => {
      e.preventDefault();
      const mes = e.target.closest('.mes');
      const dataBr = mes.querySelector('.mini-agenda .agenda-data').textContent;
      const notas = mes.querySelector('.mini-agenda .agenda-notas').value;

      if (!dataBr) {
        mes.querySelector('.mini-agenda').classList.remove('aberto');
        return;
      }

      const [d, m, y] = dataBr.split('/').map(Number);
      const iso = `${y}-${String(m).padStart(2, '0')}-${String(d).padStart(2, '0')}`;

      localStorage.setItem(chaveAgenda(iso), notas);
      mes.querySelector('.mini-agenda').classList.remove('aberto');
    });
  });

  // ========== EXPORTAR / IMPRIMIR ==========
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

  // ========== SELETOR DE ANO ==========
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

  // ========== GARANTIR VISIBILIDADE DO PAINEL DE METAS ==========
  function verificarVisibilidadeMeta(mes) {
    const metas = mes.querySelector('.painel-metas');
    if (!metas) return;
    metas.style.display = 'flex';
    metas.style.visibility = 'visible';
    metas.style.opacity = '1';
  }

  // ========== INICIALIZAÇÃO ==========
  document.querySelectorAll('.calendario .dia').forEach(atualizarDots);

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

  // ========== ÍCONES HEADER (PERFIL / LOGOUT) ==========
  const perfilIcon = document.getElementById('icon-perfil');
  if (perfilIcon) {
    perfilIcon.addEventListener('click', () => {
      window.location.href = '../perfil/perfil.php';
    });
  }

  const logoutModal = document.getElementById('logout-modal');
  const iconSair = document.getElementById('icon-sair');
  const confirmLogout = document.getElementById('confirm-logout');
  const cancelLogout = document.getElementById('cancel-logout');

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

  // ========== MODAL FOGi ==========
  const fogiBtn = document.getElementById('icon-fogi');
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
