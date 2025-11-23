document.addEventListener('DOMContentLoaded', function () {
  console.log('horario.js carregado');

  const SAVE_URL = window.HORARIO_SAVE_URL || 'salvar_horario.php';
  const HORARIO_HTML = window.HORARIO_HTML || '';

  const tabela = document.getElementById('scheduleTable');
  const tbody = tabela ? tabela.querySelector('tbody') : null;

  // Carregar horário salvo do JSON
  if (tbody && HORARIO_HTML && HORARIO_HTML.trim() !== '') {
    console.log('Carregando HTML salvo do horário...');
    tbody.innerHTML = HORARIO_HTML;
    // Garantir contenteditable nas células
    tbody.querySelectorAll('td').forEach((td, idx) => {
      td.contentEditable = true;
    });
  }

  // ---------- Funções globais (usadas no HTML via onclick) ----------

  window.salvarEdicoes = function () {
    if (!tbody) return;

    const html = tbody.innerHTML;
    console.log('Salvando horário no servidor...', SAVE_URL);

    fetch(SAVE_URL, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({ html })
    })
      .then(async (res) => {
        const txt = await res.text();
        console.log('Resposta salvar_horario.php:', res.status, txt);
        try {
          const json = JSON.parse(txt);
          if (json.status === 'ok') {
            alert('Horário salvo com sucesso!');
          } else {
            alert('Erro ao salvar horário.');
          }
        } catch (e) {
          alert('Horário salvo (retorno não JSON, ver console).');
        }
      })
      .catch((err) => {
        console.error('Erro ao salvar horário:', err);
        alert('Erro ao salvar horário. Ver console.');
      });
  };

  window.adicionarLinha = function () {
    if (!tabela) return;
    const novaLinha = tabela.insertRow(tabela.rows.length);
    // 6 colunas: horário + 5 dias
    for (let i = 0; i < 6; i++) {
      const celula = novaLinha.insertCell(i);
      celula.contentEditable = true;
      if (i === 0) {
        celula.style.backgroundColor = '#38a5ff';
        celula.style.color = 'white';
      } else {
        celula.style.backgroundColor = '#ececec';
        celula.style.color = 'black';
      }
    }
  };

  window.removerLinha = function () {
    if (!tabela) return;
    // mantém thead, nunca remove a linha de cabeçalho
    if (tabela.rows.length > 1) {
      tabela.deleteRow(tabela.rows.length - 1);
    }
  };

  window.adicionarIntervalo = function () {
    if (!tabela) return;
    const novaLinha = tabela.insertRow(tabela.rows.length);
    const celula = novaLinha.insertCell(0);
    celula.colSpan = 6;
    celula.contentEditable = true;
    celula.style.backgroundColor = '#38a5ff';
    celula.style.color = 'white';
    celula.innerHTML = 'Intervalo';
  };

  window.salvarComoPDF = function () {
    if (!tabela) return;
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    const rows = tabela.rows;
    const data = [];
    const headers = [];

    // Cabeçalho
    for (let i = 0; i < rows[0].cells.length; i++) {
      headers.push(rows[0].cells[i].textContent);
    }

    // Linhas
    for (let i = 1; i < rows.length; i++) {
      let row = [];
      for (let j = 0; j < rows[i].cells.length; j++) {
        row.push(rows[i].cells[j].textContent);
      }
      data.push(row);
    }

    // Título FOAG
    doc.setFont('helvetica', 'bold');
    doc.setFontSize(24);
    doc.text('FOAG', 10, 10);

    // Data
    const dataAtual = new Date();
    const dataFormatada = dataAtual.toLocaleDateString('pt-BR', {
      weekday: 'long',
      year: 'numeric',
      month: 'long',
      day: 'numeric',
      hour: '2-digit',
      minute: '2-digit',
      second: '2-digit'
    });
    doc.setFontSize(12);
    doc.text(`Gerado em: ${dataFormatada}`, 10, 20);

    // Tabela
    doc.autoTable({
      head: [headers],
      body: data,
      startY: 30,
      theme: 'grid',
      margin: { top: 10 },
      tableWidth: 'auto',
      headStyles: {
        fillColor: [56, 165, 255],
        textColor: [255, 255, 255],
        fontSize: 12,
        fontStyle: 'bold'
      },
      bodyStyles: {
        fillColor: [255, 255, 255],
        textColor: [56, 165, 255],
        fontSize: 10
      },
      alternateRowStyles: {
        fillColor: [240, 240, 240]
      }
    });

    doc.save('horario_escolar.pdf');
  };

  // ---------- Logout + Perfil ----------

  const logoutModal = document.getElementById('logout-modal');
  const confirmLogout = document.getElementById('confirm-logout');
  const cancelLogout = document.getElementById('cancel-logout');
  const iconSair = document.getElementById('icon-sair');
  const iconPerfil = document.getElementById('icon-perfil');

  if (iconSair && logoutModal) {
    iconSair.addEventListener('click', () => {
      logoutModal.style.display = 'flex';
    });

    confirmLogout &&
      confirmLogout.addEventListener('click', () => {
        window.location.href = '../index/index.php';
      });

    cancelLogout &&
      cancelLogout.addEventListener('click', () => {
        logoutModal.style.display = 'none';
      });

    logoutModal.addEventListener('click', (e) => {
      if (e.target === logoutModal) {
        logoutModal.style.display = 'none';
      }
    });
  }

  if (iconPerfil) {
    iconPerfil.addEventListener('click', () => {
      window.location.href = '../perfil/perfil.php';
    });
  }
});
